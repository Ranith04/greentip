using GreenTIP.Domain.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;

namespace GreenTIP.Infrastructure.Data.Configurations;

public class QueryAttachmentConfiguration : IEntityTypeConfiguration<QueryAttachment>
{
    public void Configure(EntityTypeBuilder<QueryAttachment> builder)
    {
        builder.ToTable("b_query_attachments");

        builder.HasKey(a => a.Id);
        builder.Property(a => a.Id).HasColumnName("id");

        builder.Property(a => a.QueryId).HasColumnName("query_id").IsRequired();
        builder.Property(a => a.UploadedBy).HasColumnName("uploaded_by").IsRequired();
        builder.Property(a => a.FileName).HasColumnName("file_name").HasMaxLength(255).IsRequired();
        builder.Property(a => a.FilePath).HasColumnName("file_path").HasMaxLength(500).IsRequired();
        builder.Property(a => a.FileSizeBytes).HasColumnName("file_size_bytes").IsRequired();
        builder.Property(a => a.MimeType).HasColumnName("mime_type").HasMaxLength(100).IsRequired();
        builder.Property(a => a.IsExpertResponse).HasColumnName("is_expert_response").HasDefaultValue(false);
        builder.Property(a => a.CreatedAt).HasColumnName("created_at");

        builder.HasOne(a => a.Query)
            .WithMany(q => q.Attachments)
            .HasForeignKey(a => a.QueryId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.HasOne(a => a.Uploader)
            .WithMany()
            .HasForeignKey(a => a.UploadedBy)
            .OnDelete(DeleteBehavior.Restrict);
    }
}

public class QueryReviewConfiguration : IEntityTypeConfiguration<QueryReview>
{
    public void Configure(EntityTypeBuilder<QueryReview> builder)
    {
        builder.ToTable("b_query_reviews");

        builder.HasKey(r => r.Id);
        builder.Property(r => r.Id).HasColumnName("id");

        builder.Property(r => r.QueryId).HasColumnName("query_id").IsRequired();
        builder.Property(r => r.UserId).HasColumnName("user_id").IsRequired();
        builder.Property(r => r.Rating).HasColumnName("rating").IsRequired();
        builder.Property(r => r.IsResolved).HasColumnName("is_resolved").HasMaxLength(20).HasDefaultValue("Yes");
        builder.Property(r => r.FeedbackText).HasColumnName("feedback_text");
        builder.Property(r => r.CreatedAt).HasColumnName("created_at");

        builder.HasIndex(r => r.QueryId).IsUnique();

        builder.HasOne(r => r.Query)
            .WithOne(q => q.Review)
            .HasForeignKey<QueryReview>(r => r.QueryId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.HasOne(r => r.User)
            .WithMany(u => u.Reviews)
            .HasForeignKey(r => r.UserId)
            .OnDelete(DeleteBehavior.Cascade);
    }
}

public class FaqConfiguration : IEntityTypeConfiguration<Faq>
{
    public void Configure(EntityTypeBuilder<Faq> builder)
    {
        builder.ToTable("b_faqs");
        builder.HasKey(f => f.Id);
        builder.Property(f => f.Id).HasColumnName("id");
        builder.Property(f => f.Question).HasColumnName("question").HasMaxLength(255).IsRequired();
        builder.Property(f => f.Answer).HasColumnName("answer").IsRequired();
        builder.Property(f => f.Status).HasColumnName("status").HasMaxLength(1).HasDefaultValue("1");
        builder.Property(f => f.AddedOn).HasColumnName("added_on");
    }
}

public class PageConfiguration : IEntityTypeConfiguration<Page>
{
    public void Configure(EntityTypeBuilder<Page> builder)
    {
        builder.ToTable("b_pages");
        builder.HasKey(p => p.Id);
        builder.Property(p => p.Id).HasColumnName("id");
        builder.Property(p => p.Title).HasColumnName("title").HasMaxLength(80);
        builder.Property(p => p.Alias).HasColumnName("alias").HasMaxLength(255).IsRequired();
        builder.Property(p => p.Content).HasColumnName("content");
        builder.Property(p => p.AddedOn).HasColumnName("added_on");
    }
}

public class SettingConfiguration : IEntityTypeConfiguration<Setting>
{
    public void Configure(EntityTypeBuilder<Setting> builder)
    {
        builder.ToTable("b_settings");
        builder.HasKey(s => s.Id);
        builder.Property(s => s.Id).HasColumnName("id");
        builder.Property(s => s.OptionName).HasColumnName("option_name").HasMaxLength(150).IsRequired();
        builder.Property(s => s.OptionValue).HasColumnName("option_value").IsRequired();
    }
}

public class BulkEmailLogConfiguration : IEntityTypeConfiguration<BulkEmailLog>
{
    public void Configure(EntityTypeBuilder<BulkEmailLog> builder)
    {
        builder.ToTable("b_bulk_email_log");
        builder.HasKey(b => b.Id);
        builder.Property(b => b.Id).HasColumnName("id");
        builder.Property(b => b.Subject).HasColumnName("subject").HasMaxLength(255).IsRequired();
        builder.Property(b => b.Message).HasColumnName("message").IsRequired();
        builder.Property(b => b.RecipientGroup).HasColumnName("recipient_group").HasMaxLength(50).HasDefaultValue("All Users");
        builder.Property(b => b.TotalRecipients).HasColumnName("total_recipients");
        builder.Property(b => b.SuccessfulDeliveries).HasColumnName("successful_deliveries");
        builder.Property(b => b.FailedDeliveries).HasColumnName("failed_deliveries");
        builder.Property(b => b.Status).HasColumnName("status").HasMaxLength(20).HasDefaultValue("Sent");
        builder.Property(b => b.AttachmentFileName).HasColumnName("attachment_file_name").HasMaxLength(255);
        builder.Property(b => b.AttachmentFilePath).HasColumnName("attachment_file_path").HasMaxLength(500);
        builder.Property(b => b.SentAt).HasColumnName("sent_at");
    }
}

public class AppNotificationConfiguration : IEntityTypeConfiguration<AppNotification>
{
    public void Configure(EntityTypeBuilder<AppNotification> builder)
    {
        builder.ToTable("b_notifications");
        builder.HasKey(n => n.Id);
        builder.Property(n => n.Id).HasColumnName("id");
        builder.Property(n => n.UserId).HasColumnName("user_id");
        builder.Property(n => n.Title).HasColumnName("title").HasMaxLength(255).IsRequired();
        builder.Property(n => n.Message).HasColumnName("message").IsRequired();
        builder.Property(n => n.Type).HasColumnName("type").HasMaxLength(50).HasDefaultValue("info");
        builder.Property(n => n.TargetId).HasColumnName("target_id").HasMaxLength(100);
        builder.Property(n => n.IsRead).HasColumnName("is_read").HasDefaultValue(false);
        builder.Property(n => n.CreatedAt).HasColumnName("created_at");

        builder.HasOne(n => n.User)
            .WithMany()
            .HasForeignKey(n => n.UserId)
            .OnDelete(DeleteBehavior.Cascade);
    }
}
