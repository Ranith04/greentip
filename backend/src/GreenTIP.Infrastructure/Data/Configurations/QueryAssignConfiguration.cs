using GreenTIP.Domain.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;

namespace GreenTIP.Infrastructure.Data.Configurations;

public class QueryAssignConfiguration : IEntityTypeConfiguration<QueryAssign>
{
    public void Configure(EntityTypeBuilder<QueryAssign> builder)
    {
        builder.ToTable("b_query_assign");

        builder.HasKey(qa => qa.Id);
        builder.Property(qa => qa.Id).HasColumnName("id");

        builder.Property(qa => qa.QueryId).HasColumnName("query_id").IsRequired();
        builder.Property(qa => qa.UserId).HasColumnName("user_id").IsRequired();
        builder.Property(qa => qa.ExpertAnswer).HasColumnName("expert_answer");
        builder.Property(qa => qa.Answer).HasColumnName("answer");
        builder.Property(qa => qa.AddedOn).HasColumnName("added_on");
        builder.Property(qa => qa.RespondDate).HasColumnName("respond_date");
        builder.Property(qa => qa.AdminReviewDate).HasColumnName("admin_review_date");
        builder.Property(qa => qa.Status).HasColumnName("status").HasDefaultValue(0);
        builder.Property(qa => qa.ReviewStatus).HasColumnName("review_status").HasDefaultValue(0);

        builder.Ignore(qa => qa.AssignmentStatus);

        // Relationships
        builder.HasOne(qa => qa.Query)
            .WithMany(q => q.Assignments)
            .HasForeignKey(qa => qa.QueryId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.HasOne(qa => qa.Expert)
            .WithMany(u => u.AssignedQueries)
            .HasForeignKey(qa => qa.UserId)
            .OnDelete(DeleteBehavior.Restrict);
    }
}
