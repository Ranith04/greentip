using GreenTIP.Domain.Entities;

namespace GreenTIP.Application.Interfaces;

public interface IJwtTokenGenerator
{
    string GenerateAccessToken(User user);
    string GenerateRefreshToken();
}
