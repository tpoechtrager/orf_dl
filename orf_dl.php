#!/usr/bin/env php
<?php

/***********************************************************************
 *  orf_dl                                                             *
 *  Copyright (C) 2019 Thomas Poechtrager                              *
 *  t.poechtrager@gmail.com                                            *
 *                                                                     *
 *  This program is free software; you can redistribute it and/or      *
 *  modify it under the terms of the GNU General Public License        *
 *  as published by the Free Software Foundation; either version 2     *
 *  of the License, or (at your option) any later version.             *
 *                                                                     *
 *  This program is distributed in the hope that it will be useful,    *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of     *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      *
 *  GNU General Public License for more details.                       *
 *                                                                     *
 *  You should have received a copy of the GNU General Public License  *
 *  along with this program; if not, write to the Free Software        *
 *  Foundation, Inc.,                                                  *
 *  51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.      *
 ***********************************************************************/

$is_windows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

$curl = curl_init();
$user_agent = NULL;
// "Mozilla/5.0 (X11; Linux x86_64; rv:69.0) Gecko/20100101 Firefox/69.0";

function print_err_code($line)
{
    $src = file_get_contents(__FILE__);

    if ($src === FALSE)
        return;

    $src = explode("\n", $src);
    $src_line_count = count($src);

    $start = ($line - 1) - 5;
    $end = $line + 5;

    if ($start < 0)
        $start = 0;

    if ($end >= $src_line_count)
        $end = $src_line_count - 1;


    for ($i = $start; $i < $end; ++$i)
    {
        $current_line = $i + 1;

        fprintf(STDERR, "%d: %s%s\n",
                        $current_line, $src[$i],
                        $current_line == $line ? "          <----" : "");
    }
}

function err($line, ...$msg)
{
    fprintf(STDERR, "Error on line: %d", $line);
    if (!empty($msg))
    {
        $msg_fmt = array_shift($msg);
        vfprintf(STDERR, " (".$msg_fmt.")", $msg);
    }
    fprintf(STDERR, "\n");
    echo "\n";
    print_err_code($line);
    echo "\n";
    return FALSE;
}

function check_array_key(&$array, ...$keys)
{
    foreach ($keys as $key)
    {
        if (!array_key_exists($key, $array))
        {
            if (count($keys) > 1)
                return err(__LINE__, "Array key '%s' does not exist", $key);

            return FALSE;
        }
    }

    return TRUE;
}

function get_milli_seconds()
{
    $microtime = microtime();
    $comps = explode(' ', $microtime);
    return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
}

class duration
{
    var $start_;
    var $end_;
    
    function start()
    {
        $this->start_ = get_milli_seconds();
    }

    function end()
    {
        $this->end_ = get_milli_seconds();
    }

    function duration()
    {
        if (!$this->start_) return -1;
        if (!$this->end_) return get_milli_seconds() - $this->start_;
        return $this->end_ - $this->start_;
    }
    
    function __construct()
    {
        $this->start_ = 0;
        $this->end_ = 0;
    }
}

function seconds_to_human_readable($seconds)
{
    $seconds %= 3600;
    $minutes = $seconds / 60;
    $seconds %= 60;

    return sprintf("%02d%s %02d%s", $minutes, "m", $seconds, "s");
}

function str_ends_with($str, $test)
{
    return substr_compare($str, $test, -strlen($test)) === 0;
}

function b_to_mb($b)
{
    return $b / 1024 / 1024;
}

function check_filename($filename)
{
    while (file_exists($filename))
        $filename = rand(0, 999999)."_".$filename;

    return $filename;
}


function http_request($url, $write_callback = NULL)
{
    global $curl, $user_agent, $is_windows;

    $curl_opts = [
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_URL => $url
    ];

    if ($user_agent)
        $curl_opts[CURLOPT_USERAGENT] = $user_agent;

    if ($write_callback) $curl_opts[CURLOPT_WRITEFUNCTION] = $write_callback;
    else $curl_opts[CURLOPT_RETURNTRANSFER] = 1;

    if ($is_windows)
        $curl_opts[CURLOPT_CAINFO] = "php/extras/cacert.pem";

    curl_setopt_array($curl, $curl_opts);

    $resp = curl_exec($curl);

    if ($resp === FALSE)
        return err(__LINE__, "HTTP Request failed: '%s'", curl_error($curl));

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status != 200)
        return err(__LINE__, "Invalid HTTP status (%d!=200)", $http_status);

    return $resp;
}

function parse_video_json($video_json)
{
    if (!check_array_key($video_json, "sources", "title"))
        return err(__LINE__);

    $video_id = -1;
    $video_episode_id = -1;

    if (check_array_key($video_json, "id"))
        $video_id = $video_json["id"];

    if (check_array_key($video_json, "episode_id"))
        $video_episode_id = $video_json["episode_id"];

    $video_title = $video_json["title"];
    $video_sources = $video_json["sources"];
    $video_subtitles = check_array_key($video_json, "subtitles") ? 
                       $video_json["subtitles"] : [];
    $video_urls = [];

    $video_filename = strftime("%Y_%m_%d_").$video_title;
    $video_url = null;
    $video_url_srt = null;

    $replace_characters = [
        ["ß", "ss"]
    ];

    $allowed_characters =
        "abcdefghijklmonpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".
        "0123456789öÖäÄüÜ@&";

    foreach ($replace_characters as $replace_character)
    {
        list($a, $b) = $replace_character;
        $video_filename = str_replace($a, $b, $video_filename);
    }

    $video_filename = str_split($video_filename);

    foreach ($video_filename as &$char_a)
    {
        for ($j = 0; $j < strlen($allowed_characters); ++$j)
        {
            $char_b = $allowed_characters[$j];
            if ($char_a == $char_b) continue 2;
        }
        $char_a = "_";
    }

    $video_filename = implode("", $video_filename);

    if ($video_filename > 100)
        $video_filename = substr($video_filename, 0, 100);

    foreach ($video_sources as $video_source)
    {
        if (!check_array_key($video_source, "type", "delivery", "quality", "src"))
            return err(__LINE__);

        if ($video_source["type"] != "application/x-mpegURL")
            continue;

        if ($video_source["delivery"] != "hls")
            continue;

        $video_urls[$video_source["quality"]] = $video_source["src"];
    }

    foreach ($video_subtitles as $video_subtitle)
    {
        if (!check_array_key($video_subtitle, "type", "src"))
            return err(__LINE__);

        if ($video_subtitle["type"] != "srt")
            continue;

        $video_url_srt = $video_subtitle["src"];
    }

    if (check_array_key($video_urls, "Q8C")) // HD
        $video_url = $video_urls["Q8C"];
    else if (check_array_key($video_urls, "Q6A")) // Medium
        $video_url = $video_urls["Q6A"];
    else if (check_array_key($video_urls, "Q4A")) // Low
        $video_url = $video_urls["Q4A"];

    if (!$video_url)
        return err(__LINE__);

    $video_url = explode("/", $video_url);
    array_pop($video_url); // remove playlist.m3u8
    $video_url = implode("/", $video_url);

    if (!str_ends_with($video_url, ".mp4"))
        return err(__LINE__, "Video URL '%s' does not end with '.mp4'", $video_url);

    return array(
        "id"         => $video_id,
        "episode_id" => $video_episode_id,
        "title"      => $video_title,
        "filename"   => $video_filename,
        "url"        => $video_url,
        "url_srt"    => $video_url_srt
    );
}

function get_video_urls($url, $wanted_video_id = -1)
{
    $resp = http_request($url);

    if ($resp === FALSE)
        return err(__LINE__, "Invalid video URL?");

    $json_string = strstr($resp, '<div class="jsb_ jsb_VideoPlaylist" data-jsb="');

    if (!$json_string)
        return err(__LINE__, "Could not find JSON data. Video no longer available?");

    $json_string = explode('">', $json_string);
    $json_string = explode('data-jsb="', $json_string[0]);
    $json_string = htmlspecialchars_decode($json_string[1]);

    $video_json = json_decode($json_string, true);

    if (!$video_json)
        return err(__LINE__, "Could not decode json data:\n%s\n\n", $json_string);

    if (!check_array_key($video_json, "playlist"))
        return err(__LINE__);

    $video_json_playlist = $video_json["playlist"];
    $videos_json = [];

    if (check_array_key($video_json_playlist, "gapless_video"))
    {
        $video_json = $video_json_playlist["gapless_video"];
        $video_json["title"] = $video_json_playlist["title"];
        $videos_json[] = $video_json;
    }
    else
    {
        if (!check_array_key($video_json_playlist, "videos"))
            return err(__LINE__);

        $videos_json = $video_json_playlist["videos"];
    }

    $videos = [];

    foreach ($videos_json as $video_json)
    {
        $video = parse_video_json($video_json);

        if ($video === FALSE)
            return err(__LINE__);

        if ($wanted_video_id != -1 && $video["id"] != $wanted_video_id)
            continue;

        $videos[] = $video;
    }

    return empty($videos) ? FALSE : $videos;
}

function get_chunk_urls($url)
{
    $m3u8_urls = [];
    $chunk_urls = [];

    $resp = http_request($url."/chunklist.m3u8");

    if ($resp === FALSE)
        return err(__LINE__, "Could not get chunk list [1]");

    $lines = explode("\n", $resp);

    foreach ($lines as $line)
    {
        if (str_ends_with($line, ".m3u8"))
            $m3u8_urls[] = $url."/".$line;
    }

    // If $m3u8_urls is empty then we already got the
    // .m3u8 with the chunk list.

    if (!empty($m3u8_urls))
    {
        $resp = http_request(end($m3u8_urls));

        if ($resp === FALSE)
            return err(__LINE__, "Could not get chunk list [2]");

        $lines = explode("\n", $resp);
    }

    foreach ($lines as $line)
    {
        if (str_ends_with($line, ".ts"))
            $chunk_urls[] = $url."/".$line;
    }

    if (empty($chunk_urls))
        return err(__LINE__, "Chunk URLS empty");

    return $chunk_urls;
}

function download_srt($video)
{
    $resp = http_request($video["url_srt"]);

    if ($resp === FALSE)
        return err(__LINE__, "Could not download subtitles ('%s')", $video["url_srt"]);

    $filename = $video["filename"].".srt";
    $fh = fopen($filename, "wb");

    if (!$fh)
        return err(__LINE__, "Cannot open '%s' for writing", $filename);

    $length = strlen($resp);

    if (fwrite($fh, $resp, $length) != $length)
    {
        fclose($fh);
        return err(__LINE__);
    }

    fclose($fh);
    return $filename;
}

function download_video($video)
{
    $chunk_urls = get_chunk_urls($video["url"]);

    if ($chunk_urls === FALSE)
        return err(__LINE__);

    global $fh, $fh_data_written, $chunk_size;

    $filename = check_filename($video["filename"].".ts");

    $fh = fopen($filename, "wb");
    $fh_data_written = 0;

    if (!$fh)
        return err(__LINE__, "Cannot open '%s' for writing", $filename);

    $error = false;
    $chunk_count = count($chunk_urls);

    $approx_file_size = 0;
    $prev_fh_data_written = 0;

    $duration = new duration;
    $duration_now = new duration;

    $duration->start();

    for ($i = 0; $i < $chunk_count; ++$i)
    {
        $chunk_num = $i + 1;

        if ($chunk_num == 1)
            printf("Downloading first chunk\n\n");

        $duration_now->start();

        $resp = http_request($chunk_urls[$i], function($curl, $data) {
            global $fh, $fh_data_written;
            $data_length = strlen($data); // Yes, this works.
            if (fwrite($fh, $data, $data_length) != $data_length) return 0;
            $fh_data_written += $data_length;
            return $data_length;
        });

        if ($resp === FALSE)
        {
            $error = true;
            break;
        }

        $approx_file_size = ($fh_data_written / $chunk_num) * $chunk_count;
        $p_done = (100 / $approx_file_size) * $fh_data_written;

        $bps = ($fh_data_written - $prev_fh_data_written) * (1000 / $duration_now->duration());
        $bps_avg = ($fh_data_written / $duration->duration()) * 1000;

        $seconds_remaining = ($approx_file_size - $fh_data_written) / $bps;
        $seconds_remaining_avg = ($approx_file_size - $fh_data_written) / $bps_avg;

        printf("Downloaded %d MB out of approx. %d MB -- %d%% -- Avg: %.1f MB/s -- ".
               "Curr: %.1f MB/s -- Approx. %s remaining\n",
               b_to_mb($fh_data_written), b_to_mb($approx_file_size), $p_done,
               b_to_mb($bps_avg), b_to_mb($bps),
               seconds_to_human_readable($seconds_remaining_avg));

        $prev_fh_data_written = $fh_data_written;
    }

    fclose($fh);

    return !$error ? $filename : FALSE;
}

function convert_ts_to_mp4_container($video_filename, $video_srt_filename)
{
    $video_filename_mp4 = explode(".", $video_filename);
    $video_filename_mp4[count($video_filename_mp4)-1] = "mp4";
    $video_filename_mp4 = implode(".", $video_filename_mp4);
    $video_filename_mp4 = check_filename($video_filename_mp4);

    printf("Converting %s to mp4-container\n\n", $video_filename, $video_filename_mp4);

    if ($video_srt_filename)
    {
        $command = sprintf(
            "ffmpeg -i %s -f srt -i %s -acodec copy ".
            "-bsf:a aac_adtstoasc -vcodec copy -c:s mov_text %s",
            escapeshellarg($video_filename),
            escapeshellarg($video_srt_filename),
            escapeshellarg($video_filename_mp4)
        );
    }
    else
    {
        $command = sprintf(
            "ffmpeg -i %s -acodec copy -bsf:a aac_adtstoasc ".
            "-vcodec copy %s",
            escapeshellarg($video_filename),
            escapeshellarg($video_filename_mp4)
        );
    }

    $return_code = -1;
    passthru($command, $return_code);
    return $return_code == 0;
}

function download_videos($tvthek_urls)
{
    $videos = [];

    foreach ($tvthek_urls as $tvthek_url)
    {
        $wanted_video_id = -1;

        if (sscanf($tvthek_url, "%[^:]://tvthek.orf.at/profile/%s", $dummy, $dummy) != 2)
            return err(__LINE__, "Invalid URL '%s'", $tvthek_url);

        sscanf($tvthek_url,
               "%[^:]://tvthek.orf.at/profile/%[^/]/%d/%[^/]/%d/%[^/]/%s",
               $dummy, $dummy, $dummy, $dummy, $dummy, $dummy, $wanted_video_id);

        if (count($tvthek_urls) > 1)
            printf("Processing '%s' ...\n", $tvthek_url);

        $videos_tmp = get_video_urls($tvthek_url, $wanted_video_id);

        if ($videos_tmp === FALSE)
            return err(__LINE__);

        foreach ($videos_tmp as $video)
            $videos[] = $video;
    }

    if (count($videos) > 1)
    {
        printf("Videos to download:\n\n");

        foreach ($videos as $video)
            printf("  '%s'\n", $video["filename"]);

        printf("\n");
    }

    foreach ($videos as $video)
    {
        $video_srt_filename = null;
        $video_filename = null;

        printf("Downloading '%s' ...\n\n", $video["title"]);

        if ($video["url_srt"])
        {
            $filename = download_srt($video);

            if ($filename !== FALSE)
                $video_srt_filename = $filename;
        }

        $video_filename = download_video($video);

        if ($video_filename === FALSE)
            return err(__LINE__);

        printf("\nDownloaded: $video_filename\n");

        if (convert_ts_to_mp4_container($video_filename, $video_srt_filename))
        {
            printf("\nRemoving: '%s'\n", $video_filename);
            unlink($video_filename);

            if ($video_srt_filename)
            {
                printf("Removing: '%s'\n", $video_srt_filename);
                unlink($video_srt_filename);
            }

            printf("\n");
        }

        printf("Done.\n\n");
    }

    return TRUE;
}

$tvthek_urls = [];
$error = false;

if ($argc < 2)
{
    $tvthek_urls[] = readline("ORF Mediathek URL: ");
    printf("\n");
}
else
{
    $error = false;

    for ($i = 1; $i < $argc; ++$i)
        $tvthek_urls[] = $argv[$i];
}

if (download_videos($tvthek_urls) === FALSE)
    $error = true;

curl_close($curl);

exit($error ? 1 : 0);

?>
