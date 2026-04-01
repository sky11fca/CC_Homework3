using AuthenticationService.API.Features.UserAuthentication.Login;
using FluentValidation;

namespace AuthenticationService.API.Validators;

public class LoginValidator : AbstractValidator<LoginCommand>
{
    public LoginValidator()
    {
        RuleFor(x => x.Email).NotEmpty().NotNull().EmailAddress().WithMessage("Email is required");
        RuleFor(x => x.PlainPassword).NotEmpty().NotNull().WithMessage("Password is required");
    }
}