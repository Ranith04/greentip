namespace GreenTIP.Application.Interfaces;

public interface IFileStorageService
{
    Task<(string filePath, string fileName, long fileSize)> SaveFileAsync(Stream fileStream, string originalFileName, string folder);
    Task DeleteFileAsync(string relativePath);
}
