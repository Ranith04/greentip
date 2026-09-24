using System.Reflection;
using GreenTIP.Domain.Entities;
using Microsoft.EntityFrameworkCore;

namespace GreenTIP.Infrastructure.Data;

public class GreenTipDbContext : DbContext
{
    public GreenTipDbContext(DbContextOptions<GreenTipDbContext> options) : base(options)
    {
    }

    public DbSet<User> Users => Set<User>();
    public DbSet<Query> Queries => Set<Query>();
    public DbSet<QueryAssign> QueryAssigns => Set<QueryAssign>();
    public DbSet<Category> Categories => Set<Category>();
    public DbSet<QueryAttachment> QueryAttachments => Set<QueryAttachment>();
    public DbSet<QueryReview> QueryReviews => Set<QueryReview>();
    public DbSet<Faq> Faqs => Set<Faq>();
    public DbSet<Page> Pages => Set<Page>();
    public DbSet<Setting> Settings => Set<Setting>();
    public DbSet<BulkEmailLog> BulkEmailLogs => Set<BulkEmailLog>();
    public DbSet<AppNotification> Notifications => Set<AppNotification>();

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);
        modelBuilder.ApplyConfigurationsFromAssembly(Assembly.GetExecutingAssembly());
    }
}
