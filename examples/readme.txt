4个演示工程，演示了混淆前后的差异

//////////////////////////////////////////////////////////////////////////
目录./1/
普通windows 应用程序 (编译连接器: vs2010)

实现功能：将输入数字做简单变化后输出，混淆目标：数字变化的规则算法

./1.config                           混淆配置文件

./1/demo/Release/demoDlg.org.obj     原始中间文件
./1/demo/Release/demoDlg.unest.obj   混淆后中间文件

./1/Release/demoDlg.org.exe          原始中间文件连接成的exe档
./1/Release/demoDlg.unest.exe        混淆后中间文件连接成的exe档

//////////////////////////////////////////////////////////////////////////
目录./2/ 
windows 驱动程序 (编译连接器: WDK 7600.16385.1)

实现功能：dbprint输出，混淆目标：输出字符串的构造

./2.config                                     混淆配置文件

./2/objchk_wxp_x86/i386/helloworld.org.obj     原始中间文件
./2/objchk_wxp_x86/i386/helloworld.unest.obj   混淆后中间文件

./2/objchk_wxp_x86/i386/helloworld.org.sys       原始中间文件连接成的驱动程序
./2/objchk_wxp_x86/i386/helloworld.unest.sys     混淆后中间文件连接成的驱动程序

//////////////////////////////////////////////////////////////////////////
目录./3/ 
纯代码段 (编译器: nasm)

实现功能：把目标代码段写入到栈内并跳转过去执行，混淆目标：目标代码段的写入部分

./3.config                                     混淆配置文件

./3/a            原始的native二进制代码
./3/a.result     混淆后的native二进制代码

//////////////////////////////////////////////////////////////////////////
目录./4/ 
对静态库进行混淆，进而影响所有链接的代码

实现功能: 对静态库libcmt.lib中的memset.obj进行混淆，然后再写回lib,代码使用静态连接其提供的memset函数，可以看到效果

./4.config    混淆配置文件

./4/Release/libcmt.lib.org                     vs2010自带libcmt库
./4/Release/memset.obj.org                     vs2010自带libcmt库中导出的memset.obj
./4/Release/memset.obj.unest                   经过混淆处理后的memset.obj
./4/Release/libcmt.lib.unest                   经过混淆处理后的memset.obj 导入替换后的 libcmt.lib


./4/Release/test.org.exe                       使用自带libcmt库编译的demo执行文档
./4/Release/test.unest.exe                     使用混淆处理后的libcmt库编译的demo执行文档