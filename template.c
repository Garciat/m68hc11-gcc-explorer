typedef void (*outstrg_ft)(const char*);
static outstrg_ft OUTSTRG = (outstrg_ft)(void*)0xFFC7;

typedef char (*inchar_ft)();
static inchar_ft INCHAR = (inchar_ft)(void*)0xFFCD;

typedef void (*outcrlf_ft)();
static outcrlf_ft OUTCRLF = (outcrlf_ft)(void*)0xFFC4;

typedef void (*outa_ft)();
static outa_ft OUTA = (outa_ft)(void*)0xFFB8;

typedef void (*out1byt_ft)(const void*);
static out1byt_ft OUT1BYT = (out1byt_ft)(void*)0xFFBB;

static void* Stack = (void*)0xDFFF;

static char EOT = '\x04';

#if __cplusplus
extern "C" {
#endif

// C source

void f() {
    char s[3];
    s[0] = INCHAR();
    s[1] = INCHAR();
    s[2] = EOT;
    OUTSTRG(s);
}

#if __cplusplus
}
#endif