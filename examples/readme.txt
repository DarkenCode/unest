4����ʾ���̣���ʾ�˻���ǰ��Ĳ���

//////////////////////////////////////////////////////////////////////////
Ŀ¼./1/
��ͨwindows Ӧ�ó��� (����������: vs2010)

ʵ�ֹ��ܣ��������������򵥱仯�����������Ŀ�꣺���ֱ仯�Ĺ����㷨

./1.config                           ���������ļ�

./1/demo/Release/demoDlg.org.obj     ԭʼ�м��ļ�
./1/demo/Release/demoDlg.unest.obj   �������м��ļ�

./1/Release/demoDlg.org.exe          ԭʼ�м��ļ����ӳɵ�exe��
./1/Release/demoDlg.unest.exe        �������м��ļ����ӳɵ�exe��

//////////////////////////////////////////////////////////////////////////
Ŀ¼./2/ 
windows �������� (����������: WDK 7600.16385.1)

ʵ�ֹ��ܣ�dbprint���������Ŀ�꣺����ַ����Ĺ���

./2.config                                     ���������ļ�

./2/objchk_wxp_x86/i386/helloworld.org.obj     ԭʼ�м��ļ�
./2/objchk_wxp_x86/i386/helloworld.unest.obj   �������м��ļ�

./2/objchk_wxp_x86/i386/helloworld.org.sys       ԭʼ�м��ļ����ӳɵ���������
./2/objchk_wxp_x86/i386/helloworld.unest.sys     �������м��ļ����ӳɵ���������

//////////////////////////////////////////////////////////////////////////
Ŀ¼./3/ 
������� (������: nasm)

ʵ�ֹ��ܣ���Ŀ������д�뵽ջ�ڲ���ת��ȥִ�У�����Ŀ�꣺Ŀ�����ε�д�벿��

./3.config                                     ���������ļ�

./3/a            ԭʼ��native�����ƴ���
./3/a.result     �������native�����ƴ���

//////////////////////////////////////////////////////////////////////////
Ŀ¼./4/ 
�Ծ�̬����л���������Ӱ���������ӵĴ���

ʵ�ֹ���: �Ծ�̬��libcmt.lib�е�memset.obj���л�����Ȼ����д��lib,����ʹ�þ�̬�������ṩ��memset���������Կ���Ч��

./4.config    ���������ļ�

./4/Release/libcmt.lib.org                     vs2010�Դ�libcmt��
./4/Release/memset.obj.org                     vs2010�Դ�libcmt���е�����memset.obj
./4/Release/memset.obj.unest                   ��������������memset.obj
./4/Release/libcmt.lib.unest                   ��������������memset.obj �����滻��� libcmt.lib


./4/Release/test.org.exe                       ʹ���Դ�libcmt������demoִ���ĵ�
./4/Release/test.unest.exe                     ʹ�û���������libcmt������demoִ���ĵ�