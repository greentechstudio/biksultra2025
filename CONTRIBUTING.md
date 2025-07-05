# Contributing to ASRRUN

Terima kasih atas minat Anda untuk berkontribusi pada ASRRUN! Panduan ini akan membantu Anda memahami cara berkontribusi pada proyek ini.

## 🤝 Cara Berkontribusi

### 1. Fork Repository
```bash
# Fork repository di GitHub
# Clone fork Anda ke local
git clone https://github.com/[your-username]/ASRRUN.git
cd ASRRUN
```

### 2. Setup Development Environment
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed
```

### 3. Create Feature Branch
```bash
# Buat branch baru untuk fitur/bugfix
git checkout -b feature/amazing-feature
# atau
git checkout -b bugfix/fix-something
```

### 4. Make Changes
- Pastikan kode mengikuti coding standards
- Tambahkan tests jika diperlukan
- Update dokumentasi jika diperlukan

### 5. Test Changes
```bash
# Run tests
php artisan test

# Test fitur secara manual
php artisan serve
```

### 6. Commit Changes
```bash
# Commit dengan pesan yang jelas
git add .
git commit -m "feat: add amazing feature"

# atau untuk bugfix
git commit -m "fix: resolve issue with payment processing"
```

### 7. Push and Create Pull Request
```bash
# Push ke fork Anda
git push origin feature/amazing-feature

# Buat Pull Request di GitHub
```

## 📋 Coding Standards

### PHP Code Style
- Ikuti PSR-12 coding standard
- Gunakan meaningful variable names
- Tambahkan docblocks untuk functions/classes
- Maximum line length: 120 characters

### Laravel Best Practices
- Gunakan Eloquent ORM untuk database operations
- Implement proper validation
- Use form requests for complex validation
- Follow Laravel naming conventions

### JavaScript/Frontend
- Use ES6+ syntax
- Consistent indentation (2 spaces)
- Meaningful function/variable names
- Comment complex logic

### Database
- Use descriptive table/column names
- Always create migrations for schema changes
- Add indexes for performance
- Use foreign key constraints

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/WhatsAppQueueTest.php

# Run with coverage
php artisan test --coverage
```

### Writing Tests
- Write tests for new features
- Test both happy path and edge cases
- Use descriptive test method names
- Mock external API calls

### Test Examples
```php
// Feature test example
public function test_user_can_register_with_valid_data()
{
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'phone' => '628123456789'
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com'
    ]);
}
```

## 📚 Documentation

### Code Documentation
- Add docblocks for all public methods
- Document complex algorithms
- Update README.md for new features
- Include usage examples

### API Documentation
- Document all API endpoints
- Include request/response examples
- Specify required parameters
- Document error responses

## 🐛 Bug Reports

### Before Reporting
1. Check existing issues
2. Reproduce the bug
3. Check if it's already fixed in latest version

### Bug Report Template
```
**Bug Description**
A clear description of the bug.

**Steps to Reproduce**
1. Go to '...'
2. Click on '....'
3. See error

**Expected Behavior**
What should happen.

**Actual Behavior**
What actually happens.

**Environment**
- OS: [e.g., Windows 10]
- PHP Version: [e.g., 8.1.2]
- Laravel Version: [e.g., 10.0]
- Browser: [e.g., Chrome 91]

**Screenshots**
If applicable, add screenshots.

**Additional Context**
Any other context about the problem.
```

## 💡 Feature Requests

### Before Requesting
1. Check if feature already exists
2. Search existing feature requests
3. Consider if it fits project scope

### Feature Request Template
```
**Feature Description**
A clear description of the feature.

**Problem Statement**
What problem does this solve?

**Proposed Solution**
How should this feature work?

**Alternatives Considered**
Other solutions you've considered.

**Additional Context**
Any other context about the feature.
```

## 🚀 Release Process

### Version Numbering
- Follow Semantic Versioning (semver.org)
- MAJOR.MINOR.PATCH
- MAJOR: Breaking changes
- MINOR: New features (backward compatible)
- PATCH: Bug fixes

### Release Checklist
- [ ] Update version in composer.json
- [ ] Update CHANGELOG.md
- [ ] Run all tests
- [ ] Update documentation
- [ ] Create release tag
- [ ] Deploy to production

## 📞 Getting Help

### Community Support
- GitHub Issues: Bug reports and feature requests
- GitHub Discussions: General questions and ideas
- Email: [support@asrrun.com]

### Development Questions
- Check existing documentation
- Search closed issues
- Ask in GitHub Discussions

## 📄 Code of Conduct

### Our Pledge
We pledge to make participation in our project a harassment-free experience for everyone, regardless of age, body size, disability, ethnicity, gender identity and expression, level of experience, nationality, personal appearance, race, religion, or sexual identity and orientation.

### Our Standards
- Using welcoming and inclusive language
- Being respectful of differing viewpoints
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

### Enforcement
Report unacceptable behavior to [conduct@asrrun.com].

## 🎯 Priority Areas

### High Priority
- Security improvements
- Performance optimizations
- Bug fixes
- Documentation updates

### Medium Priority
- New features
- UI/UX improvements
- Test coverage
- Code refactoring

### Low Priority
- Nice-to-have features
- Experimental features
- Style improvements

## 🛠️ Development Tools

### Recommended Tools
- IDE: PhpStorm, VS Code
- Database: MySQL Workbench, phpMyAdmin
- API Testing: Postman, Insomnia
- Git GUI: SourceTree, GitKraken

### Useful Commands
```bash
# Code formatting
./vendor/bin/php-cs-fixer fix

# Static analysis
./vendor/bin/phpstan analyse

# Security check
composer audit

# Generate IDE helper
php artisan ide-helper:generate
```

## 🏆 Recognition

### Contributors
All contributors will be recognized in:
- README.md contributors section
- CHANGELOG.md for their contributions
- GitHub contributors page

### Types of Contributions
- Code contributions
- Documentation improvements
- Bug reports
- Feature suggestions
- Testing
- Code reviews

---

Thank you for contributing to ASRRUN! 🚀
