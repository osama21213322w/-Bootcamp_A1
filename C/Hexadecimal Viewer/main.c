#include <stdio.h>
#define PAUSE_IF_INTERACTIVE() \
    do { if (interactive) { printf("Enter exit!!!!"); getchar(); } } while (0)

int main(int argc, char *argv[])
{
    FILE *file;
    unsigned char buffer[16];
    int bytesRead, i, offset = 0, c;
    char fileName[260];
    int interactive = (argc < 2);
    if (interactive)
    {
        printf("Enter file name: ");
        scanf("%259s", fileName);
        while ((c = getchar()) != '\n' && c != EOF);}
    else
    {
        snprintf(fileName, sizeof(fileName), "%s", argv[1]);}
    file = fopen(fileName, "rb");
    if (file == NULL)
    {
        printf("Error: Cannot open file.\n");
        PAUSE_IF_INTERACTIVE();
        return 1;
    }

    printf("\nOffset     Hexadecimal Values!!!                      ASCII\n");
    printf("------------------------------------------------------------\n");

    while ((bytesRead = fread(buffer, 1, 16, file)) > 0)
    {
        printf("%08X   ", offset);
        for (i = 0; i < bytesRead; i++) printf("%02X ", buffer[i]);
        for (; i < 16; i++) printf("   ");
        printf("  ");
        for (i = 0; i < bytesRead; i++)
            putchar((buffer[i] >= 32 && buffer[i] <= 126) ? buffer[i] : '.');
        printf("\n");
        offset += bytesRead;}
    fclose(file);
    printf("\nFile completed!!!!!!!!!.\n");
    PAUSE_IF_INTERACTIVE();
    return 0;
}
