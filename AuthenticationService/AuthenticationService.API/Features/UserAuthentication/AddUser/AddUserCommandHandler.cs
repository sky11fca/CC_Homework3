using AuthenticationService.API.Persistance;
using AuthenticationService.API.Validators;
using FluentValidation;
using Microsoft.EntityFrameworkCore;

namespace AuthenticationService.API.Features.UserAuthentication.AddUser;

public class AddUserCommandHandler(IValidator<AddUserCommand> validator, AuthenticationDbContext context)
{
    public async Task<IResult> Handle(AddUserCommand command)
    {
        var validationResult = validator.Validate(command);
        if (!validationResult.IsValid)
        {
            throw new ValidationException(validationResult.Errors);
        }
        
        var existingUser = await context.Users.FirstOrDefaultAsync(u => u.Email == command.Email || u.Username == command.Username);
        if (existingUser != null)
        {
            return Results.BadRequest("User with this email or username already exists");
        }

        var hashedPassword = BCrypt.Net.BCrypt.HashPassword(command.PlainPassword);
        var user = new User(Guid.NewGuid(), command.Username, command.Email, hashedPassword, DateTime.Now);
        await context.Users.AddAsync(user);
        await context.SaveChangesAsync();
        return Results.Created($"/auth/{user.Id}", user);
    }
}