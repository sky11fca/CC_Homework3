using AuthenticationService.API.Features.UserAuthentication.AddUser;
using FluentValidation;

namespace AuthenticationService.API.Validators;

public class AddUserValidator : AbstractValidator<AddUserCommand>
{
    public AddUserValidator()
    {
        RuleFor(x => x.Username).NotEmpty().NotNull().WithMessage("Username is required");
        RuleFor(x => x.Email).NotEmpty().NotNull().EmailAddress().WithMessage("Email is required");
        RuleFor(x => x.PlainPassword).NotEmpty().NotNull().WithMessage("Password is required").MinimumLength(8)
            .WithMessage("Password must be at least 8 characters long");
    }
}