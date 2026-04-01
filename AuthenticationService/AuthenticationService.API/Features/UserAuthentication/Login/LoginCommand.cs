namespace AuthenticationService.API.Features.UserAuthentication.Login;

public record LoginCommand(
    string Email,
    string PlainPassword
    );