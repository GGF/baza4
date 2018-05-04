.PHONY: clean All

All:
	@echo ----------Building project:[ pcbtype - Release ]----------
	@"mingw32-make.exe"  -j 1 -f "pcbtype.mk"
clean:
	@echo ----------Cleaning project:[ pcbtype - Release ]----------
	@"mingw32-make.exe"  -j 1 -f "pcbtype.mk" clean
