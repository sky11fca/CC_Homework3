FROM mcr.microsoft.com/dotnet/sdk:10.0 AS build
WORKDIR /src

COPY AuthenticationService.API/AuthenticationService.API.csproj AuthenticationService.API/
RUN dotnet restore AuthenticationService.API/AuthenticationService.API.csproj

COPY . .
RUN dotnet publish AuthenticationService.API/AuthenticationService.API.csproj -c Release -o /app/publish /p:UseAppHost=false

FROM mcr.microsoft.com/dotnet/aspnet:10.0 AS final
WORKDIR /app
COPY --from=build /app/publish .

# App Engine custom runtimes route traffic to port 8080.
ENV ASPNETCORE_URLS=http://0.0.0.0:8080
EXPOSE 8080

ENTRYPOINT ["dotnet", "AuthenticationService.API.dll"]