@echo off

rem Source: https://unix.stackexchange.com/questions/40638/how-to-do-i-convert-an-animated-gif-to-an-mp4-or-mv4-on-the-command-line

if exist *.webm del /q *.webm
if exist *.mp4 del /q *.mp4
if exist *.ogv del /q *.ogv

set filename=number-up--how-to-play--

rem goto do-magick
goto do-ffmpeg

:do-ffmpeg

ffmpeg -i %filename%en.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%en.mp4
ffmpeg -i %filename%en.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%en.webm
ffmpeg -i %filename%en.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%en.ogv

ffmpeg -i %filename%es.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%es.mp4
ffmpeg -i %filename%es.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%es.webm
ffmpeg -i %filename%es.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%es.ogv

ffmpeg -i %filename%fr.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%fr.mp4
ffmpeg -i %filename%fr.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%fr.webm
ffmpeg -i %filename%fr.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%fr.ogv

ffmpeg -i %filename%de.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%de.mp4
ffmpeg -i %filename%de.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%de.webm
ffmpeg -i %filename%de.gif -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %filename%de.ogv

goto end

:skip-ffmpeg

rem additional delegates needed for the ffmpeg component of the conversion:
rem <delegate decode="gif" encode="mp4" command="&quot;ffmpeg.exe&quot; -nostdin -loglevel error -i &quot;%i&quot; -movflags faststart -pix_fmt yuv420p -vf &quot;scale=trunc(iw/2)*2:trunc(ih/2)*2&quot; -f mp4 -y &quot;%o&quot;"/>
rem
rem ALSO: the magick conversions are larger than the ffmpeg conversions, reason unknown.

:do-magick

magick %filename%en.gif -define video:pixel-format=yuv420p %filename%en.webm
magick %filename%es.gif -define video:pixel-format=yuv420p %filename%es.webm
magick %filename%fr.gif -define video:pixel-format=yuv420p %filename%fr.webm
magick %filename%de.gif -define video:pixel-format=yuv420p %filename%de.webm

magick %filename%en.gif -define video:pixel-format=yuv420p %filename%en.mp4
magick %filename%es.gif -define video:pixel-format=yuv420p %filename%es.mp4
magick %filename%fr.gif -define video:pixel-format=yuv420p %filename%fr.mp4
magick %filename%de.gif -define video:pixel-format=yuv420p %filename%de.mp4

magick %filename%en.gif -define video:pixel-format=yuv420p %filename%en.ogv
magick %filename%es.gif -define video:pixel-format=yuv420p %filename%es.ogv
magick %filename%fr.gif -define video:pixel-format=yuv420p %filename%fr.ogv
magick %filename%de.gif -define video:pixel-format=yuv420p %filename%de.ogv

goto end

:end

set filename=
