using GreenTIP.Domain.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;

namespace GreenTIP.Infrastructure.Data.Configurations;

public class CategoryConfiguration : IEntityTypeConfiguration<Category>
{
    public void Configure(EntityTypeBuilder<Category> builder)
    {
        builder.ToTable("b_categories");

        builder.HasKey(c => c.Id);
        builder.Property(c => c.Id).HasColumnName("id");

        builder.Property(c => c.CatName).HasColumnName("cat_name").HasMaxLength(255).IsRequired();
        builder.Property(c => c.Alias).HasColumnName("alias").HasMaxLength(255).IsRequired();
        builder.Property(c => c.Status).HasColumnName("status").HasMaxLength(1).HasDefaultValue("1");
        builder.Property(c => c.AddedOn).HasColumnName("added_on");
    }
}
