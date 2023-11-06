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
			cwebp "%%~nf.jpg" -q 80 -o "%%~nf.webp"
		)
	)
	if exist "*.png" (
		for %%f in (*.png) do (
			cwebp "%%~nf.png" -q 80 -o "%%~nf.webp"
		)
	)
	cd
rem leave the directory
	popd
)
rem pause