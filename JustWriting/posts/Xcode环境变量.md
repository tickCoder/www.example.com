<!--
 Xcode环境变量
-->

- ${SRCROOT}

> 工程文件如Nuno.xcodeproj的路径，注意是大括号，不是小括号。
>

- ${PROJECT_FILE_PATH}

> xxx.xcodeproj文件路径
> 

- ${DERIVED_FILE_DIR}

> 默认为～/Library/Developer/Xcode/DerivedData
> 

- $(CURRENT_PROJECT_VERSION),

>  当前工程版本号
>

- $(BUILT_PRODUCTS_DIR)

> build成功后最终产品路径, 可以在Build Settings参数的Per-configuration Build Products Path项里设置
> 

- $(TARGET_NAME)

> 工程目标名称
> 

- $(基础路径DerivedData)

> 一些拥有共同的基础路径，暂以此代替，未设置任何Build Settings参数时，默认的模拟器中为：
> 
>   /Users/xxx/Library/Developer/Xcode/DerivedData/xxxWorkspace-caepeadwrerdcrftijaolkkagbjf
> 

- $(SYMROOT)

> $(基础路径DerivedData)/Build/Products
> 
> 不会随着Build Settings参数的设置而改变
> 

- $(BUILD_DIR)

> $(基础路径DerivedData)/Build/Products
> 
> 不会随着Build Settings参数的设置而改变
> 

- $(BUILD_ROOT))

> $(基础路径DerivedData)/Build/Products
> 
> 不会随着Build Settings参数的设置而改变
> 

- $(CONFIGURATION_BUILD_DIR)

>  $(基础路径DerivedData)/Build/Products/Debug-iphonesimulator
>  
>  可以通过设置而改变
>  

- $(BUILT_PRODUCTS_DIR)

>  $(基础路径DerivedData)/Build/Products/Debug-iphonesimulator
>  
>  可以通过设置而改变
>  
 
- $(CONFIGURATION_TEMP_DIR)

>  $(基础路径DerivedData)/Build/Intermediates/target_name.build/Debug-iphonesimulator
>  
>  可以通过设置而改变
>  

- $(TARGET_BUILD_DIR)

>  $(基础路径DerivedData)/Build/Intermediates/Debug-iphonesimulator
>  
>  可以通过设置而改变
>  

- $(SDK_NAME)

>  当前SDK的名字，如iphonesimulator8.0等
>  

- $(PLATFORM_NAME) 

> 当前程序运行的平台，如iphonesimulator等
> 

- $(CONFIGURATION)

> Debug/Release等
> 

- $(EXECUTABLE_NAME)

> 可执行文件名，如libXML.a 
> 

- $(PHONEOS_DEPLOYMENT_TARGET)

> 如8.0 
> 

- $(ACTION)

> 当前操作如编译操作,build,install(archive)
> 

- $(CURRENTCONFIG_SIMULATOR_DIR) 

> 当前模拟器路径: ${SYMROOT}/${CONFIGURATION}-iphonesimulator
> 

- $(CURRENTCONFIG_DEVICE_DIR) 

> 当前设备路径: ${SYMROOT}/${CONFIGURATION}-iphoneos
> 

- 组合${CONFIGURATION}-iphoneos 

> 表示，如Debug-iphoneos
> 

- 自定义变量$(CREATING_UNIVERSAL_DIR)

> 如$(CREATING_UNIVERSAL_DIR) = ${SYMROOT}/${CONFIGURATION}-universal
> 表示$(基础路径DerivedData)/Build/Products/Release-universal
> 