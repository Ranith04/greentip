FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /app

# Copy solution and project files
COPY GreenTIP.sln ./
COPY src/GreenTIP.Domain/GreenTIP.Domain.csproj src/GreenTIP.Domain/
COPY src/GreenTIP.Application/GreenTIP.Application.csproj src/GreenTIP.Application/
COPY src/GreenTIP.Infrastructure/GreenTIP.Infrastructure.csproj src/GreenTIP.Infrastructure/
COPY src/GreenTIP.Api/GreenTIP.Api.csproj src/GreenTIP.Api/
COPY tests/GreenTIP.Infrastructure.Tests/GreenTIP.Infrastructure.Tests.csproj tests/GreenTIP.Infrastructure.Tests/

RUN dotnet restore GreenTIP.sln

# Copy all source files and publish
COPY . .
WORKDIR /app/src/GreenTIP.Api
RUN dotnet publish -c Release -o /out

FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS runtime
WORKDIR /app
COPY --from=build /out .
EXPOSE 8080
ENTRYPOINT ["dotnet", "GreenTIP.Api.dll"]
