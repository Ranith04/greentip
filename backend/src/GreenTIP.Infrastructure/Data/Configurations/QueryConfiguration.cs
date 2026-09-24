using GreenTIP.Domain.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;

namespace GreenTIP.Infrastructure.Data.Configurations;

public class QueryConfiguration : IEntityTypeConfiguration<Query>
{
    public void Configure(EntityTypeBuilder<Query> builder)
    {
        builder.ToTable("b_queries");

        builder.HasKey(q => q.Id);
        builder.Property(q => q.Id).HasColumnName("id");

        builder.Property(q => q.UserId).HasColumnName("user_id").IsRequired();
        builder.Property(q => q.CategoryId).HasColumnName("category_id");
        builder.Property(q => q.QueryText).HasColumnName("query");
        builder.Property(q => q.Status).HasColumnName("status").HasDefaultValue(0);
        builder.Property(q => q.AddedOn).HasColumnName("added_on");
        builder.Property(q => q.ModifiedOn).HasColumnName("modified_on");

        // Structured fields for mobile (additive columns)
        builder.Property(q => q.QueryCode).HasColumnName("query_code").HasMaxLength(50);
        builder.Property(q => q.QueryTitle).HasColumnName("query_title").HasMaxLength(255);
        builder.Property(q => q.ProjectType).HasColumnName("project_type").HasMaxLength(100);
        builder.Property(q => q.Location).HasColumnName("location").HasMaxLength(255);
        builder.Property(q => q.ProjectAreaSqm).HasColumnName("project_area_sqm");

        builder.Ignore(q => q.QueryStatus);

        // Relationships
        builder.HasOne(q => q.User)
            .WithMany(u => u.SubmittedQueries)
            .HasForeignKey(q => q.UserId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.HasOne(q => q.Category)
            .WithMany(c => c.Queries)
            .HasForeignKey(q => q.CategoryId)
            .OnDelete(DeleteBehavior.SetNull);

        builder.HasMany(q => q.Assignments)
            .WithOne(qa => qa.Query)
            .HasForeignKey(qa => qa.QueryId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.HasMany(q => q.Attachments)
            .WithOne(a => a.Query)
            .HasForeignKey(a => a.QueryId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.HasOne(q => q.Review)
            .WithOne(r => r.Query)
            .HasForeignKey<QueryReview>(r => r.QueryId)
            .OnDelete(DeleteBehavior.Cascade);
    }
}
