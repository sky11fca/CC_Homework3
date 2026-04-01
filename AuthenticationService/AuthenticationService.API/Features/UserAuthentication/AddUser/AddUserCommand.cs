namespace AuthenticationService.API.Features.UserAuthentication.AddUser;

public record AddUserCommand(
    string Username,
    string Email,
    string PlainPassword
    );