# TYPO3 MVP Quick Start Guide

Get the TYPO3 Dentist Directory MVP up and running in minutes!

## ⚡ 5-Minute Setup (Linux/Mac)

```bash
# 1. Clone the repository
git clone <your-repo>
cd typo3-demo

# 2. Initialize directories and environment
make init

# 3. Build and start everything
make setup

# Done! Access at http://localhost
```

## ⚡ Quick Start for Windows (PowerShell)

```powershell
# If using WSL2 with Ubuntu
wsl

# Then run the Linux commands above
```

## 📍 Where to Access Services

After running `make setup`, open your browser:

| Service | URL | Notes |
|---------|-----|-------|
| **Frontend** | http://localhost | Your public website |
| **Backend** | http://localhost/typo3 | Admin panel |
| **Email Testing** | http://localhost:8025 | MailHog - catches all emails |
| **Database Admin** | http://localhost:8080 | phpMyAdmin |

## 🔑 Default Credentials

| Service | User | Password |
|---------|------|----------|
| Database (PostgreSQL) | typo3 | typo3secure123 |
| phpMyAdmin | typo3 | typo3secure123 |
| TYPO3 Backend | Set during first install | Set during first install |

## 🎯 First Steps in TYPO3

1. **Visit** http://localhost
2. **You'll see** the TYPO3 installer
3. **Follow the** on-screen instructions
4. **Create your** admin account
5. **Set up your** first site
6. **Build your** dentist directory!

## 📝 Useful Commands

```bash
# Stop services (without removing)
make down

# Restart services
make restart

# View logs
make logs

# Clear TYPO3 cache
make flush-cache

# Access database shell
make shell-db

# Get help
make help
```

## 🐛 Stuck? Common Issues

### Services won't start
```bash
make clean
make setup
```

### Can't connect to database
```bash
make logs-db
# Check the output for errors
```

### Port already in use
Edit `.env` and change the ports, then restart

### Permission denied
```bash
docker-compose exec app chmod -R 755 var/ fileadmin/ typo3temp/
```

## 📚 Need More Help?

- **Full Documentation**: See `DOCKER_SETUP.md`
- **Docker Issues**: `make logs` (check what's failing)
- **TYPO3 Issues**: https://docs.typo3.org/

## 🚀 Next: Deploy to Production

When ready to go live:

1. Edit `.env` with production values
2. Follow "Production Checklist" in `DOCKER_SETUP.md`
3. Configure your domain
4. Set up SSL certificates
5. Deploy!

---

**Happy building! 🎉**

