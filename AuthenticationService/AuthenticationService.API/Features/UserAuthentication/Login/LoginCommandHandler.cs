using AuthenticationService.API.Features.JwtGenerator.Generate;
using AuthenticationService.API.Persistance;
using FluentValidation;
using Microsoft.EntityFrameworkCore;

namespace AuthenticationService.API.Features.UserAuthentication.Login;

public class LoginCommandHandler(IValidator<LoginCommand> validator, AuthenticationDbContext context, GenerateCommandHandler jwtGenerator)
{
    public async Task<IResult> Handler(LoginCommand command)
    {
        var validationResult = validator.Validate(command);
        if (!validationResult.IsValid)
        {
            return Results.BadRequest("Could not validate your request");
        }

        var existingUser = await context.Users.FirstOrDefaultAsync(u => u.Email == command.Email);
        if (existingUser == null)
        {
            return Results.NotFound("User not found");
        }

        if (!BCrypt.Net.BCrypt.Verify(command.PlainPassword, existingUser.HashedPassword))
        {
            return Results.BadRequest("Password did not match");
        }
        
        var token = jwtGenerator.Generate(existingUser);

        return Results.Created($"/auth/{token}", token);
    }
}