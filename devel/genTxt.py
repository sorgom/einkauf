from sys import argv
from os.path import dirname

head = """Sample input for copy & paste
@ Hochseekapitänskajütentürschlüsselmanufaktur
Hochseekapitänskajütentürersatzschlüssel
Zweitschlüssel für Hochseekapitänskajütentür um sicher zu gehen"""

cFuncs = {
    'ko': lambda cnr : f'@ {cnr:02d} 장',
    'de': lambda cnr : f'@ Top {cnr:02d}',
    'en': lambda cnr : f'@ top {cnr:02d}',
}
iFuncs = {
    'ko': lambda cnr, inr : f'{cnr:02d} 장 항목 {inr:02d}',
    'de': lambda cnr, inr : f'Top {cnr:02d} Punkt {inr:02d}',
    'en': lambda cnr, inr : f'top {cnr:02d} item {inr:02d}',
}
eFuncs = {
    'ko': lambda cnr : f'@ {cnr:02d} 빈',
    'de': lambda cnr : f'@ Leer {cnr:02d}',
    'en': lambda cnr : f'@ empty {cnr:02d}',
}

code = 'de'
for c in argv[1:2]: code = c

cFunc = cFuncs.get(code, cFuncs['en'])
iFunc = iFuncs.get(code, iFuncs['en'])
eFunc = eFuncs.get(code, eFuncs['en'])

trg = f'{dirname(__file__)}/tmp.sample.input.{code}.txt'
with open(trg, 'w', encoding='utf-8') as fh:
    def pr(c:str): print(c, file=fh)
    pr(head)
    ne = 1
    np = 1
    for c in range(1,20,3):
        pr(cFunc(c))
        for i in range(1, c + 1):
            pr(iFunc(c, i))
        pr(eFunc(ne))
        ne += 1
        pr('@ ?')
        pr('? 99')
        for n in range(1,4):
            pr(f'? {np:02d}')
            np += 1
    for c in range(20,30):
        pr(cFunc(c))
        for i in range(1, 3):
            pr(iFunc(c, i))
