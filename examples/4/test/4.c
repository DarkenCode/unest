int main(){	
	char test[100] = {0};
	_asm{int 03h}
	memset (&test,0x31,99);
	printf("%s\n",test);
	return 0;
}