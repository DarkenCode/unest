/* oacrlink error LNK2019: unresolved external symbol @__security_check_cookie@4 referenced in function _DriverEntry@8
******************************************************************************* 
*= = �ļ����ƣ�HelloWorld.c 
*= = �ļ����������������HelloWorld���� 
*= = ��    �ߣ������辶 
*= = ��дʱ�䣺2009-04-23 21:16:00 
******************************************************************************* 
*/ 

#include <NTDDK.h> 
//*============================================================================
//*= = �������ƣ�DriverEntry 
//*= = ��������������������ں��� 
//*= = ��ڲ�����PDRIVER_OBJECT, PUNICODE_STRING 
//*= = ���ڲ�����NTSTATUS 
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
//*= = �ļ����� 
//*============================================================================
