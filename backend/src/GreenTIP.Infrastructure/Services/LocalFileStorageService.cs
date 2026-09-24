using GreenTIP.Application.Interfaces;
using Microsoft.Extensions.Configuration;

namespace GreenTIP.Infrastructure.Services;

public class LocalFileStorageService : IFileStorageService
{
    private readonly string _baseUploadPath;

    public LocalFileStorageService(IConfiguration configuration)
    {
        var configuredPath = configuration["FileStorage:BasePath"];
        _baseUploadPath = !string.IsNullOrEmpty(configuredPath) 
            ? configuredPath 
            : Path.Combine(Directory.GetCurrentDirectory(), "uploads");

        if (!Directory.Exists(_baseUploadPath))
        {
            Directory.CreateDirectory(_baseUploadPath);
        }
    }

    public async Task<(string filePath, string fileName, long fileSize)> SaveFileAsync(Stream fileStream, string originalFileName, string folder)
    {
        var targetFolder = Path.Combine(_baseUploadPath, folder);
        if (!Directory.Exists(targetFolder))
        {
            Directory.CreateDirectory(targetFolder);
        }

        var ext = Path.GetExtension(originalFileName);
        var uniqueFileName = $"{Guid.NewGuid():N}{ext}";
        var fullPath = Path.Combine(targetFolder, uniqueFileName);

        using (var output = new FileStream(fullPath, FileMode.Create))
        {
            await fileStream.CopyToAsync(output);
        }

        var fileInfo = new FileInfo(fullPath);
        var relativePath = Path.Combine("uploads", folder, uniqueFileName).Replace('\\', '/');

        return (relativePath, originalFileName, fileInfo.Length);
    }

    public Task DeleteFileAsync(string relativePath)
    {
        var fullPath = Path.Combine(Directory.GetCurrentDirectory(), relativePath.TrimStart('/'));
        if (File.Exists(fullPath))
        {
            File.Delete(fullPath);
        }
        return Task.CompletedTask;
    }
}
