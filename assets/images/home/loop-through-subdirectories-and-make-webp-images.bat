@echo off
rem Loop through all subdirectories off of current directory and perform commands:

for /r %%a in (.) do (
rem enter the directory
	pushd %%a
rem delete any existing webp files:
	if exist "*.webp" (
		del *.webp
	)
	if exist "*.jpg" (
		for %%f in (*.jpg) do (
			convert %%~nf.jpg -sampling-factor 4:2:0 -strip -quality 80 -interlace JPEG -colorspace sRGB %%~nf.webp
rem			cwebp "%%~nf.jpg" -q 80 -o "%%~nf.webp"
		)
	)
	if exist "*.png" (
		for %%f in (*.png) do (
			convert %%~nf.png -sampling-factor 4:2:0 -strip -quality 80 -interlace JPEG -colorspace sRGB %%~nf.webp
rem			cwebp "%%~nf.png" -q 80 -o "%%~nf.webp"
		)
	)
	cd
rem leave the directory
	popd
)
rem pause