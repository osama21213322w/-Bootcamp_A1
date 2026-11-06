#include <stdio.h>
#include <stdbool.h>

// osama ALsalahi

void check_it_01(const char *password, bool *hasU, bool *hasL, bool *hasD, bool *hasS)
{
    if (*password == '\0') { return; }

    if (*password >= 'A' && *password <= 'Z') *hasU = true;
    else if (*password >= 'a' && *password <= 'z') *hasL = true;
    else if (*password >= '0' && *password <= '9') *hasD = true;
    else *hasS = true;

    check_it_01(password + 1, hasU, hasL, hasD, hasS);
}

int len_rec_2(const char *password)
{
    if (*password == '\0')
        return 0;
    return 1 + len_rec_2(password + 1);
}

bool valid_len_3(const char *password)
{
    int x = len_rec_2(password);
    return x > 8;
}

bool all_ok_4(bool upper, bool lower, bool digit, bool symbol)
{
    return upper && lower && digit && symbol;
}

float percent_5(bool lenOK, bool upper_ok, bool lower_ok, bool digit_ok, bool symbol_ok)
{
    int score = 0;

    if (lenOK) score++;
    if (upper_ok) score++;
    if (lower_ok) score++;
    if (digit_ok) score++;
    if (symbol_ok) score++;

    return ((float)score / 5.0) * 100.0;
}

void show_msg_6(bool lenOK, bool upper_ok, bool lower_ok, bool digit_ok, bool symbol_ok)
{
    if (!lenOK) {
        printf("\n[Weak password!] Must be more than 8 characters.\n");
        return;
    }

    if (upper_ok && lower_ok && digit_ok && symbol_ok) {
        printf("\n[Strong password!] Meets all requirements.\n");
    } else {
        printf("\n[Weak password!] Missing requirements:\n");
        if (!upper_ok)  printf("- Missing Uppercase letter.\n");
        if (!lower_ok)  printf("- Missing lowercase letter.\n");
        if (!digit_ok)  printf("- Missing Number.\n");
        if (!symbol_ok) printf("- Missing Symbol.\n");
    }
}

int main()
{
    char pass[100];

    bool hasU = false;
    bool hasL = false;
    bool hasD = false;
    bool hasS = false;

    printf("Enter password: ");
    scanf("%99s", pass);

    bool lenOK = valid_len_3(pass);

    if (lenOK) {
        check_it_01(pass, &hasU, &hasL, &hasD, &hasS);
    }

    show_msg_6(lenOK, hasU, hasL, hasD, hasS);

    if (lenOK && all_ok_4(hasU, hasL, hasD, hasS)) {
        float st = percent_5(lenOK, hasU, hasL, hasD, hasS);
        printf("Password Strength: %.2f%%\n", st);
    }

    return 0;
}
