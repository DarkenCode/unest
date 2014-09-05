[bits 32]

db 0cch
mov eax,esp
sub eax,20h
push eax
push eax
call myFunc
ret

myFunc:
mov dword [eax],0cccccccch
mov dword [eax+4],6c6c6548h
mov dword [eax+8],6f572c6fh
mov dword [eax+12],21646c72h
mov  byte [eax+16],0h
ret 4