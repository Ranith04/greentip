using FluentValidation;
using GreenTIP.Application.Interfaces;
using GreenTIP.Application.Services;
using Microsoft.Extensions.DependencyInjection;

namespace GreenTIP.Application;

public static class DependencyInjection
{
    public static IServiceCollection AddApplicationServices(this IServiceCollection services)
    {
        services.AddValidatorsFromAssembly(typeof(DependencyInjection).Assembly);

        services.AddScoped<IAuthService, AuthService>();
        services.AddScoped<IDashboardService, DashboardService>();
        services.AddScoped<IQueryService, QueryService>();
        services.AddScoped<IUserService, UserService>();
        services.AddScoped<IExpertService, ExpertService>();
        services.AddScoped<IBulkEmailService, BulkEmailService>();
        services.AddScoped<IKnowledgeService, KnowledgeService>();
        services.AddScoped<INotificationService, NotificationService>();

        return services;
    }
}
