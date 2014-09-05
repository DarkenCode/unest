/* oacrlink error LNK2019: unresolved external symbol @__security_check_cookie@4 referenced in function _DriverEntry@8
******************************************************************************* 
*= = 文件名称：HelloWorld.c 
*= = 文件描述：驱动程序的HelloWorld例子 
*= = 作    者：竹林蹊径 
*= = 编写时间：2009-04-23 21:16:00 
******************************************************************************* 
*/ 

#include <NTDDK.h> 
//*============================================================================
//*= = 函数名称：DriverEntry 
//*= = 功能描述：驱动程序入口函数 
//*= = 入口参数：PDRIVER_OBJECT, PUNICODE_STRING 
//*= = 出口参数：NTSTATUS 
//*============================================================================
#pragma code_seg (".text$unest_here") 
int myTest(char * buf){
    unsigned int * iBuf = (int *)buf;
    * iBuf = 0x6c6c6548;
    iBuf ++;
    * iBuf = 0x6f572c6f;
    iBuf ++;
    * iBuf = 0x21646c72;
    //"Hello, Windows Driver!"    
    return 0;
}
#pragma code_seg () 

NTSTATUS
DriverEntry (
    __in PDRIVER_OBJECT DriverObject,
    __in PUNICODE_STRING RegistryPath
    )
{
    char buf[100] = {0};
    _asm{pushad}
    myTest(&buf); 
    _asm{popad}
    DbgPrint(buf);    
    return STATUS_SUCCESS;
}
//*============================================================================
//*= = 文件结束 
//*============================================================================
