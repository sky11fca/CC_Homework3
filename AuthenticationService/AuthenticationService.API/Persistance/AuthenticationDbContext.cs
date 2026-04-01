using AuthenticationService.API.Features.UserAuthentication;
using Microsoft.EntityFrameworkCore;

namespace AuthenticationService.API.Persistance;

public class AuthenticationDbContext(DbContextOptions<AuthenticationDbContext> options): DbContext(options)
{
    public DbSet<User> Users { get; set;}
}