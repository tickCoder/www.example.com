<!--
Xcode开发常用脚本命令
-->

- 编译脚本(真机、模拟器)

> xcodebuild -project "XML.xcodeproj" -configuration "Debug" -target "XML" -sdk "iphoneos5.0" -arch "armv6 armv7 armv7s arm64" build RUN_CLANG_STATIC_ANALYZER=NO  $(BUILD_DIR)="${BUILD_DIR}" BUILD_ROOT="${BUILD_ROOT}"
> 
> 它的意义为：使用xodebuild编译“XML”工程，使用Debug配置，编译Target为XML，使用SDK为iphoneos5.0，架构为armv6、armv7..，是否运行静态分析器，如果要输出到文件，加入"${BUILD_ROOT}.build_output"
> 
> 如果要编译模拟器的，将iphoneos5.0，替换为"iphonesimulator5.0"，架构为"i386 x86_64"
> 

- lipo脚本

> 合并iPhone模拟器和真机的静态类库，生成通用库universal,如
> 
> lipo -create -output "${UNIVERSAL_DIR}/${EXECUTABLE_NAME}" "${CURRENTCONFIG_DEVICE_DIR}/${EXECUTABLE_NAME}" "${CURRENTCONFIG_SIMULATOR_DIR}/${EXECUTABLE_NAME}"
> 
> 意思是：把"${CURRENTCONFIG_DEVICE_DIR}目录下的.a文件，和${CURRENTCONFIG_SIMULATOR_DIR}目录下的.a文件合并，在${UNIVERSAL_DIR}目录下，生成两个设备都通用的静态库,
> 
> 例如：lipo -create -output debug-universal.a debug-iphoneos.a debug-iphonesimulator.a
> 

- 查看静态库，动态库支持的架构

> lipo -info libXXX.a，lipo -info XXFramework
> 

