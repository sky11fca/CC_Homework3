FROM mcr.microsoft.com/dotnet/aspnet:10.0 AS base
USER $APP_UID
WORKDIR /app
EXPOSE 5000

# Create the directory for the SQLite database and set permissions
USER root
RUN mkdir -p /app/data && chown -R app:app /app/data
USER app

FROM mcr.microsoft.com/dotnet/sdk:10.0 AS build
ARG BUILD_CONFIGURATION=Release
WORKDIR /src
COPY ["./AuthenticationService.API/AuthenticationService.API.csproj", "./AuthenticationService.API/"]
RUN dotnet restore "./AuthenticationService.API/AuthenticationService.API.csproj"
COPY . .
RUN dotnet build "./AuthenticationService.API/AuthenticationService.API.csproj" -c $BUILD_CONFIGURATION -o /app/build

FROM build AS publish
ARG BUILD_CONFIGURATION=Release
RUN dotnet publish "./AuthenticationService.API/AuthenticationService.API.csproj" -c $BUILD_CONFIGURATION -o /app/publish /p:UseAppHost=false

FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "AuthenticationService.API.dll"]