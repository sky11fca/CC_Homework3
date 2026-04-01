using AuthenticationService.API.Features.JwtGenerator.Generate;
using AuthenticationService.API.Features.UserAuthentication.AddUser;
using AuthenticationService.API.Features.UserAuthentication.Login;
using AuthenticationService.API.Persistance;
using Microsoft.EntityFrameworkCore;
using Microsoft.OpenApi;
using FluentValidation;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddOpenApi();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddDbContext<AuthenticationDbContext>(options => options.UseSqlite(builder.Configuration.GetConnectionString("DefaultConnection") ?? "Data Source=usermanagement.db"));
builder.Services.AddScoped<GenerateCommandHandler>();
builder.Services.AddScoped<LoginCommandHandler>();
builder.Services.AddScoped<AddUserCommandHandler>();

builder.Services.AddValidatorsFromAssemblyContaining<Program>();

builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll", policy =>
    {
        policy.AllowAnyOrigin()
              .AllowAnyMethod()
              .AllowAnyHeader();
    });
});

builder.Services.AddSwaggerGen(c =>
{
    c.SwaggerDoc(
        "v1",
            new OpenApiInfo()
            {
                Title = "User Authentification Service",
                Version = "v1",
                Contact = new OpenApiContact()
                {
                    Name = "Mr. Bogdanovich",
                    Email = "bogdan.bzn03@protonmail.com"
                }
            }
        );
});
    

var app = builder.Build();

using (var scope = app.Services.CreateScope())
{
    var context = scope.ServiceProvider.GetRequiredService<AuthenticationDbContext>();
    context.Database.EnsureCreated();
}

if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI(c =>
    {
        c.SwaggerEndpoint("/swagger/v1/swagger.json", "User Authentification Service v1");
        c.RoutePrefix = string.Empty;
        c.DisplayRequestDuration();
    });
    app.MapOpenApi();
}

app.UseHttpsRedirection();

app.UseCors("AllowAll");


app.MapPost("/api/auth/register", async (AddUserCommand req, AddUserCommandHandler handler) =>
    {
        return await handler.Handle(req);
    })
    .WithDisplayName("UserRegister")
    .WithName("UserRegister");

app.MapPost("/api/auth/login", async (LoginCommand req, LoginCommandHandler handler) =>
{
   return await handler.Handler(req);
}).WithDisplayName("UserLogin")
.WithName("UserLogin");

app.Run();
