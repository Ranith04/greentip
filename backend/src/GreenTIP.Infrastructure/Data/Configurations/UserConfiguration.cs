using GreenTIP.Domain.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;

namespace GreenTIP.Infrastructure.Data.Configurations;

public class UserConfiguration : IEntityTypeConfiguration<User>
{
    public void Configure(EntityTypeBuilder<User> builder)
    {
        builder.ToTable("b_users");

        builder.HasKey(u => u.Id);
        builder.Property(u => u.Id).HasColumnName("id");

        builder.Property(u => u.RoleType)
            .HasColumnName("role_type")
            .HasMaxLength(1)
            .IsRequired()
            .HasDefaultValue("2");

        builder.Property(u => u.Name).HasColumnName("name").HasMaxLength(75);
        builder.Property(u => u.Email).HasColumnName("email").HasMaxLength(150);
        builder.Property(u => u.Password).HasColumnName("password").HasMaxLength(255);
        builder.Property(u => u.Image).HasColumnName("image").HasMaxLength(255);
        builder.Property(u => u.ContactNo).HasColumnName("contact_no");
        builder.Property(u => u.Description).HasColumnName("description");
        builder.Property(u => u.ActivationCode).HasColumnName("activation_code").HasMaxLength(255);
        builder.Property(u => u.Linkedin).HasColumnName("linkdin").HasMaxLength(255);
        builder.Property(u => u.Facebook).HasColumnName("facebook").HasMaxLength(255);

        builder.Property(u => u.Status)
            .HasColumnName("status")
            .HasMaxLength(1)
            .HasDefaultValue("1");

        builder.Property(u => u.CreatedOn).HasColumnName("created_on");
        builder.Property(u => u.UpdatedOn).HasColumnName("updated_on");
        builder.Property(u => u.IpAddress).HasColumnName("ip_address").HasMaxLength(32);
        builder.Property(u => u.Token).HasColumnName("token");
        builder.Property(u => u.LastLogin).HasColumnName("last_login");
        builder.Property(u => u.Username).HasColumnName("username").HasMaxLength(255);
        builder.Property(u => u.CompanyName).HasColumnName("company_name").HasMaxLength(255);
        builder.Property(u => u.Occupation).HasColumnName("occupation").HasMaxLength(255);
        builder.Property(u => u.EducationQualification).HasColumnName("education_qualification").HasMaxLength(255);
        builder.Property(u => u.ExpertiseField).HasColumnName("expertise_field").HasMaxLength(255);

        // Ignore computed properties
        builder.Ignore(u => u.Role);

        // Relationships
        builder.HasMany(u => u.SubmittedQueries)
            .WithOne(q => q.User)
            .HasForeignKey(q => q.UserId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.HasMany(u => u.AssignedQueries)
            .WithOne(qa => qa.Expert)
            .HasForeignKey(qa => qa.UserId)
            .OnDelete(DeleteBehavior.Restrict);

        builder.HasMany(u => u.Reviews)
            .WithOne(r => r.User)
            .HasForeignKey(r => r.UserId)
            .OnDelete(DeleteBehavior.Cascade);
    }
}
