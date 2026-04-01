namespace AuthenticationService.API.Features.UserAuthentication;

public record User(
    Guid Id,
    string Username,
    string Email,
    string HashedPassword,
    DateTime CreatedAt
    );