using GreenTIP.Infrastructure.Security;
using Xunit;

namespace GreenTIP.Infrastructure.Tests;

public class PasswordHasherTests
{
    private readonly BCryptPasswordHasher _hasher = new();

    [Fact]
    public void HashPassword_ShouldGenerateValidBCryptHash()
    {
        // Arrange
        var rawPassword = "SecurePassword@2026";

        // Act
        var hash = _hasher.HashPassword(rawPassword);

        // Assert
        Assert.NotNull(hash);
        Assert.StartsWith("$2", hash); // Standard BCrypt prefix
        Assert.True(_hasher.VerifyPassword(rawPassword, hash));
    }

    [Fact]
    public void VerifyPassword_ShouldReturnFalse_WhenPasswordIsIncorrect()
    {
        // Arrange
        var rawPassword = "CorrectPassword123";
        var wrongPassword = "WrongPassword456";
        var hash = _hasher.HashPassword(rawPassword);

        // Act & Assert
        Assert.False(_hasher.VerifyPassword(wrongPassword, hash));
    }

    [Fact]
    public void VerifyPassword_ShouldReturnFalse_WhenInputIsNullOrEmpty()
    {
        // Arrange
        var hash = _hasher.HashPassword("TestPass");

        // Act & Assert
        Assert.False(_hasher.VerifyPassword("", hash));
        Assert.False(_hasher.VerifyPassword("TestPass", ""));
        Assert.False(_hasher.VerifyPassword("", ""));
    }

    [Theory]
    [InlineData("admin123")]
    [InlineData("password")]
    [InlineData("123456")]
    [InlineData("triazine@123")]
    public void HashAndVerify_MultiplePasswords_ShouldAllSucceed(string password)
    {
        var hash = _hasher.HashPassword(password);
        Assert.True(_hasher.VerifyPassword(password, hash));
    }
}
