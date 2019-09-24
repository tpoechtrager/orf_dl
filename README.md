### Description ###

ORF Mediathek Downloader written in PHP.

`orf_dl` is a console application.

### Pre-requisites ###

`PHP` and `PHP curl module`.

Optional: `ffmpeg`.

FFmpeg is required if you want to the tool to convert your downloaded
videos into an mp4-container without re-encoding.

### Usage ###

`php orf_dl.php <ORF Mediathek URL>`

    $ php orf_dl.php https://tvthek.orf.at/profile/ZIB-700/12288226/ZIB-700/14026786
    Downloading 'ZIB_7_00' ...

    Downloading first chunk

    Downloaded 4 MB out of approx. 207 MB -- 2% -- Avg: 10.7 MB/s -- Curr: 10.7 MB/s -- Approx. 00m 19s remaining
    Downloaded 8 MB out of approx. 200 MB -- 4% -- Avg: 11.8 MB/s -- Curr: 13.2 MB/s -- Approx. 00m 16s remaining
    Downloaded 12 MB out of approx. 195 MB -- 6% -- Avg: 12.0 MB/s -- Curr: 12.7 MB/s -- Approx. 00m 15s remaining
    Downloaded 16 MB out of approx. 193 MB -- 8% -- Avg: 12.1 MB/s -- Curr: 12.5 MB/s -- Approx. 00m 14s remaining
    Downloaded 20 MB out of approx. 197 MB -- 10% -- Avg: 12.6 MB/s -- Curr: 14.4 MB/s -- Approx. 00m 14s remaining
    Downloaded 24 MB out of approx. 195 MB -- 12% -- Avg: 12.9 MB/s -- Curr: 14.6 MB/s -- Approx. 00m 13s remaining
    Downloaded 28 MB out of approx. 195 MB -- 14% -- Avg: 13.3 MB/s -- Curr: 17.1 MB/s -- Approx. 00m 12s remaining
    Downloaded 32 MB out of approx. 195 MB -- 16% -- Avg: 13.6 MB/s -- Curr: 16.2 MB/s -- Approx. 00m 11s remaining
    Downloaded 36 MB out of approx. 193 MB -- 18% -- Avg: 6.4 MB/s -- Curr: 1.1 MB/s -- Approx. 00m 24s remaining
    Downloaded 39 MB out of approx. 191 MB -- 20% -- Avg: 4.3 MB/s -- Curr: 1.0 MB/s -- Approx. 00m 34s remaining
    Downloaded 43 MB out of approx. 190 MB -- 22% -- Avg: 3.3 MB/s -- Curr: 1.0 MB/s -- Approx. 00m 44s remaining
    Downloaded 48 MB out of approx. 192 MB -- 25% -- Avg: 2.5 MB/s -- Curr: 0.7 MB/s -- Approx. 00m 58s remaining
    Downloaded 52 MB out of approx. 192 MB -- 27% -- Avg: 2.6 MB/s -- Curr: 7.9 MB/s -- Approx. 00m 54s remaining
    Downloaded 55 MB out of approx. 191 MB -- 29% -- Avg: 2.7 MB/s -- Curr: 14.7 MB/s -- Approx. 00m 49s remaining
    Downloaded 59 MB out of approx. 191 MB -- 31% -- Avg: 2.9 MB/s -- Curr: 13.9 MB/s -- Approx. 00m 45s remaining
    Downloaded 63 MB out of approx. 191 MB -- 33% -- Avg: 3.0 MB/s -- Curr: 14.8 MB/s -- Approx. 00m 41s remaining
    Downloaded 67 MB out of approx. 191 MB -- 35% -- Avg: 3.2 MB/s -- Curr: 13.1 MB/s -- Approx. 00m 38s remaining
    Downloaded 71 MB out of approx. 190 MB -- 37% -- Avg: 3.3 MB/s -- Curr: 13.8 MB/s -- Approx. 00m 35s remaining
    Downloaded 76 MB out of approx. 192 MB -- 39% -- Avg: 3.5 MB/s -- Curr: 14.4 MB/s -- Approx. 00m 33s remaining
    Downloaded 80 MB out of approx. 192 MB -- 41% -- Avg: 3.6 MB/s -- Curr: 13.8 MB/s -- Approx. 00m 30s remaining
    Downloaded 84 MB out of approx. 192 MB -- 43% -- Avg: 3.8 MB/s -- Curr: 14.6 MB/s -- Approx. 00m 28s remaining
    Downloaded 88 MB out of approx. 192 MB -- 45% -- Avg: 3.9 MB/s -- Curr: 14.7 MB/s -- Approx. 00m 26s remaining
    Downloaded 91 MB out of approx. 191 MB -- 47% -- Avg: 3.4 MB/s -- Curr: 0.9 MB/s -- Approx. 00m 29s remaining
    Downloaded 96 MB out of approx. 192 MB -- 50% -- Avg: 3.5 MB/s -- Curr: 15.1 MB/s -- Approx. 00m 27s remaining
    Downloaded 100 MB out of approx. 192 MB -- 52% -- Avg: 2.9 MB/s -- Curr: 0.6 MB/s -- Approx. 00m 31s remaining
    Downloaded 104 MB out of approx. 192 MB -- 54% -- Avg: 3.0 MB/s -- Curr: 4.6 MB/s -- Approx. 00m 29s remaining
    Downloaded 108 MB out of approx. 192 MB -- 56% -- Avg: 3.0 MB/s -- Curr: 10.5 MB/s -- Approx. 00m 27s remaining
    Downloaded 112 MB out of approx. 192 MB -- 58% -- Avg: 3.1 MB/s -- Curr: 14.2 MB/s -- Approx. 00m 25s remaining
    Downloaded 116 MB out of approx. 193 MB -- 60% -- Avg: 3.2 MB/s -- Curr: 15.8 MB/s -- Approx. 00m 23s remaining
    Downloaded 120 MB out of approx. 192 MB -- 62% -- Avg: 3.3 MB/s -- Curr: 16.6 MB/s -- Approx. 00m 21s remaining
    Downloaded 124 MB out of approx. 192 MB -- 64% -- Avg: 3.4 MB/s -- Curr: 15.8 MB/s -- Approx. 00m 20s remaining
    Downloaded 128 MB out of approx. 192 MB -- 66% -- Avg: 3.5 MB/s -- Curr: 16.2 MB/s -- Approx. 00m 18s remaining
    Downloaded 132 MB out of approx. 192 MB -- 68% -- Avg: 3.6 MB/s -- Curr: 15.9 MB/s -- Approx. 00m 16s remaining
    Downloaded 136 MB out of approx. 192 MB -- 70% -- Avg: 3.7 MB/s -- Curr: 16.0 MB/s -- Approx. 00m 15s remaining
    Downloaded 140 MB out of approx. 192 MB -- 72% -- Avg: 3.7 MB/s -- Curr: 15.9 MB/s -- Approx. 00m 13s remaining
    Downloaded 144 MB out of approx. 193 MB -- 75% -- Avg: 3.8 MB/s -- Curr: 15.1 MB/s -- Approx. 00m 12s remaining
    Downloaded 148 MB out of approx. 193 MB -- 77% -- Avg: 3.8 MB/s -- Curr: 3.4 MB/s -- Approx. 00m 11s remaining
    Downloaded 152 MB out of approx. 192 MB -- 79% -- Avg: 3.9 MB/s -- Curr: 15.8 MB/s -- Approx. 00m 10s remaining
    Downloaded 156 MB out of approx. 192 MB -- 81% -- Avg: 3.8 MB/s -- Curr: 2.2 MB/s -- Approx. 00m 09s remaining
    Downloaded 160 MB out of approx. 192 MB -- 83% -- Avg: 3.8 MB/s -- Curr: 5.3 MB/s -- Approx. 00m 08s remaining
    Downloaded 164 MB out of approx. 192 MB -- 85% -- Avg: 3.7 MB/s -- Curr: 1.5 MB/s -- Approx. 00m 07s remaining
    Downloaded 168 MB out of approx. 192 MB -- 87% -- Avg: 3.8 MB/s -- Curr: 15.9 MB/s -- Approx. 00m 06s remaining
    Downloaded 172 MB out of approx. 192 MB -- 89% -- Avg: 3.8 MB/s -- Curr: 15.8 MB/s -- Approx. 00m 05s remaining
    Downloaded 176 MB out of approx. 192 MB -- 91% -- Avg: 3.8 MB/s -- Curr: 2.0 MB/s -- Approx. 00m 04s remaining
    Downloaded 180 MB out of approx. 192 MB -- 93% -- Avg: 3.7 MB/s -- Curr: 2.0 MB/s -- Approx. 00m 03s remaining
    Downloaded 184 MB out of approx. 192 MB -- 95% -- Avg: 3.6 MB/s -- Curr: 2.4 MB/s -- Approx. 00m 02s remaining
    Downloaded 188 MB out of approx. 192 MB -- 97% -- Avg: 3.6 MB/s -- Curr: 2.6 MB/s -- Approx. 00m 01s remaining
    Downloaded 191 MB out of approx. 191 MB -- 100% -- Avg: 3.5 MB/s -- Curr: 1.6 MB/s -- Approx. 00m 00s remaining

    Downloaded: ZIB_7_00.ts
    Converting ZIB_7_00.ts to mp4-container

    ffmpeg version n4.2 Copyright (c) 2000-2019 the FFmpeg developers
    built with gcc 9.1.0 (GCC)
    configuration: --prefix=/usr --disable-debug --disable-static --disable-stripping --enable-fontconfig --enable-gmp --enable-gnutls --enable-gpl --enable-ladspa --enable-libaom --enable-libass --enable-libbluray --enable-libdav1d --enable-libdrm --enable-libfreetype --enable-libfribidi --enable-libgsm --enable-libiec61883 --enable-libjack --enable-libmodplug --enable-libmp3lame --enable-libopencore_amrnb --enable-libopencore_amrwb --enable-libopenjpeg --enable-libopus --enable-libpulse --enable-libsoxr --enable-libspeex --enable-libssh --enable-libtheora --enable-libv4l2 --enable-libvidstab --enable-libvorbis --enable-libvpx --enable-libwebp --enable-libx264 --enable-libx265 --enable-libxcb --enable-libxml2 --enable-libxvid --enable-nvdec --enable-nvenc --enable-omx --enable-shared --enable-version3
    libavutil      56. 31.100 / 56. 31.100
    libavcodec     58. 54.100 / 58. 54.100
    libavformat    58. 29.100 / 58. 29.100
    libavdevice    58.  8.100 / 58.  8.100
    libavfilter     7. 57.100 /  7. 57.100
    libswscale      5.  5.100 /  5.  5.100
    libswresample   3.  5.100 /  3.  5.100
    libpostproc    55.  5.100 / 55.  5.100
    [mpegts @ 0x5574016a4d80] start time for stream 0 is not set in estimate_timings_from_pts
    Input #0, mpegts, from 'ZIB_7_00.ts':
    Duration: 00:08:04.08, start: 0.000000, bitrate: 3319 kb/s
    Program 1 
        Stream #0:0[0x102]: Data: timed_id3 (ID3  / 0x20334449)
        Stream #0:1[0x100]: Video: h264 (Main) ([27][0][0][0] / 0x001B), yuv420p(tv, bt709, progressive), 1280x720 [SAR 1:1 DAR 16:9], 25 fps, 25 tbr, 90k tbn, 50 tbc
        Stream #0:2[0x101]: Audio: aac (LC) ([15][0][0][0] / 0x000F), 48000 Hz, stereo, fltp, 194 kb/s
    Output #0, mp4, to 'ZIB_7_00.mp4':
    Metadata:
        encoder         : Lavf58.29.100
        Stream #0:0: Video: h264 (Main) (avc1 / 0x31637661), yuv420p(tv, bt709, progressive), 1280x720 [SAR 1:1 DAR 16:9], q=2-31, 25 fps, 25 tbr, 90k tbn, 90k tbc
        Stream #0:1: Audio: aac (LC) (mp4a / 0x6134706D), 48000 Hz, stereo, fltp, 194 kb/s
    Stream mapping:
    Stream #0:1 -> #0:0 (copy)
    Stream #0:2 -> #0:1 (copy)
    Press [q] to stop, [?] for help
    frame=12091 fps=0.0 q=-1.0 Lsize=  190004kB time=00:08:04.11 bitrate=3215.2kbits/s speed= 880x    
    video:178295kB audio:11351kB subtitle:0kB other streams:0kB global headers:0kB muxing overhead: 0.189200%

    Removing: 'ZIB_7_00.ts'


    Done.
