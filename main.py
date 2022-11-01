import re

def createMap(parts=[]):
    def checkArrIndex(arr, i):
        return  arr[i] if i < len(arr) else ''
    textmap = []
    for k in parts:
        rem = k
        for i in range(0, len(k)):
            if len(rem) > 0:
                if checkArrIndex(rem, 0) == "V" and checkArrIndex(rem, 1) != "C":
                    textmap.append(1)
                    rem = rem[1:]
                elif checkArrIndex(rem, 0) == "V" and checkArrIndex(rem, 1) == "C" and checkArrIndex(rem, 2) == "V":
                    textmap.append(1)
                    rem = rem[1:]
                elif checkArrIndex(rem, 0) == "V" and checkArrIndex(rem, 1) == "C" and checkArrIndex(rem, 2) != "V" and checkArrIndex(rem, 3) != "C":
                    textmap.append(2)
                    rem = rem[2:]
                elif checkArrIndex(rem, 0) == "V" and checkArrIndex(rem, 1) == "C" and checkArrIndex(rem, 2) == "C" and checkArrIndex(rem, 3) != "V" :
                    textmap.append(3)
                    rem = rem[3:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "V" and checkArrIndex(rem, 2) != "C":
                    textmap.append(2)
                    rem = rem[2:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "V" and checkArrIndex(rem, 2) == "C" and checkArrIndex(rem, 3) == "V":
                    textmap.append(2)
                    rem = rem[2:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "V" and checkArrIndex(rem, 2) == "C" and checkArrIndex(rem, 3) == "C" and checkArrIndex(rem, 4) == "V" or checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "V" and checkArrIndex(rem, 2) == "C" and checkArrIndex(rem, 3) != "C" and checkArrIndex(rem, 3) != "V":
                    textmap.append(3)
                    rem = rem[3:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "V" and checkArrIndex(rem, 2) == "C" and checkArrIndex(rem, 3) == "C" and checkArrIndex(rem, 4) != "V":
                    textmap.append(4)
                    rem = rem[4:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "C" and checkArrIndex(rem, 2) == "V" and checkArrIndex(rem, 3) != "C":
                    textmap.append(3)
                    rem = rem[3:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "C" and checkArrIndex(rem, 2) == "V" and checkArrIndex(rem, 3) == "C" and checkArrIndex(rem, 4) == "V":
                    textmap.append(3)
                    rem = rem[3:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "C" and checkArrIndex(rem, 2) == "V" and checkArrIndex(rem, 3) == "C" and checkArrIndex(rem, 4) != "V" and checkArrIndex(rem, 5) != "C":
                    textmap.append(4)
                    rem = rem[4:]
                elif checkArrIndex(rem, 0) == "C" and checkArrIndex(rem, 1) == "C" and checkArrIndex(rem, 2) == "V" and checkArrIndex(rem, 3) == "C" and checkArrIndex(rem, 4) == "C" and checkArrIndex(rem, 5) != "V":
                    textmap.append(5)
                    rem = rem[5:]
    return textmap

def tokenizefunc(word):
    if word == "a'lo":
        return "a’-lo"
    def aCorrect(txt):
        txt = txt.lower()
        txt = txt.replace("gʻ","ğ")
        txt = txt.replace("gʼ","ğ")
        txt = txt.replace("g'","ğ")
        txt = txt.replace("g`","ğ")
        txt = txt.replace("oʻ","ŏ")
        txt = txt.replace('o’',"ŏ")
        txt = txt.replace("o`","ŏ")
        txt = txt.replace("o'","ŏ")
        txt = txt.replace("sh", 'š')
        txt = txt.replace('ch', 'č')
        txt = txt.replace("‘","’")
        txt = txt.replace("`","’")
        txt = txt.replace('ʻ', '’')
        txt = txt.replace('ʼ', '’')
        txt = txt.replace("'", '’')
        txt = txt.replace('`', '’')
        return txt
    def iCorrect(txt):
        txt = txt.lower()
        txt = txt.replace('ğ', 'g‘')
        txt = txt.replace('ŏ', 'o‘')
        txt = txt.replace('š', 'sh')
        txt = txt.replace('č', 'ch')
        return txt
    
    rgx = '/^[abdefghijklmnopqrstuvxyzŏğšč’]+$/u'
    word = aCorrect(word)
    text = re.sub(r'([aoueiŏ])', r'V', word)
    text = re.sub(r'([bdfghjklmnpqrstvxyzğšč])', r'C', text)
    parts = text.split("’", 1)
    textmap = createMap(parts)
    rem = word
    r = ""
    k = 0
    for i in textmap:
        v = i
        if v == "D":
            r += "’"
            rem = rem[:1]
        else:
            sl = rem[0:v]
            rem = rem[v:]
            if k == 0:
                r += sl
                k+=len(rem)
            else:
                r += "-" + sl
    return re.search(f'{rgx}', r, flags=re.IGNORECASE) or iCorrect(r) or False


while True:
    soz = input("soz kiriting: ")
    a = tokenizefunc(soz)
    print(a)
