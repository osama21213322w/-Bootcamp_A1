#include <stdio.h>
#include <string.h>
int main(int argc, char *argv[])
{
    FILE *file;
    char fileName[260];
    char line[1024];
    char keyword[50];
    int lineNumber = 0;
    int alertCount = 0;
    if (argc < 2)
    {
     printf("Enter log file name: ");
    scanf("%259s", fileName);   }
    else   {
     snprintf(fileName, sizeof(fileName), "%s", argv[1]);
    }
    file = fopen(fileName, "r");
    if (file == NULL)
    {
        printf("Error: Cannot open log file.\n");
        return 1;  }
    printf("Enter (FAILED, ERROR, 404): ");
    scanf("%49s", keyword);

    printf("\nSearching : %s\n", keyword);
    printf("----------------------------------------\n");
    while (fgets(line, sizeof(line), file) != NULL)
    {
        lineNumber++;
        if (strstr(line, keyword) != NULL)
        {
            alertCount++;
            printf("[ALERT] Line %d:\n", lineNumber);
            printf("%s", line);
            if (strchr(line, '\n') == NULL)
                printf("\n");
            printf("----------------------------------------\n");
        }}

    printf("\nLog Analysis Completed.\n");
    printf("Total lines checked: %d\n", lineNumber);
    printf("Total alerts found: %d\n", alertCount);
    fclose(file);
    return 0;}
