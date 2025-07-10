# Contributing to Foz da Cova Dashboard

Thank you for your interest in contributing to the Foz da Cova Dashboard project! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### Types of Contributions

We welcome various types of contributions:

- **🐛 Bug Reports**: Help us identify and fix issues
- **✨ Feature Requests**: Suggest new functionality
- **📝 Documentation**: Improve guides and documentation
- **🎨 UI/UX Improvements**: Enhance the user experience
- **🔧 Code Contributions**: Submit pull requests with improvements
- **🧪 Testing**: Help test features and report issues
- **🌐 Translations**: Help translate the interface

### Before You Start

1. **Read the documentation**:
   - [README.md](README.md) - Project overview
   - [FOZ_DA_COVA_CONCEPT.md](FOZ_DA_COVA_CONCEPT.md) - Project vision
   - [FOZ_DA_COVA_STRUCTURE.md](FOZ_DA_COVA_STRUCTURE.md) - Technical architecture

2. **Check existing issues** to avoid duplicates
3. **Join our community** discussions

## 🚀 Development Setup

### Prerequisites
- Modern web browser
- Git
- Text editor (VS Code, Sublime Text, etc.)
- Local web server (optional)

### Getting Started

1. **Fork the repository**
   ```bash
   # Clone your fork
   git clone https://github.com/YOUR_USERNAME/fozdacova-dashboard.git
   cd fozdacova-dashboard
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes** following the coding standards

4. **Test your changes**
   - Open pages in different browsers
   - Test on mobile devices
   - Validate HTML/CSS
   - Check accessibility

5. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: add new feature description"
   ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**

## 📋 Coding Standards

### HTML Guidelines
- Use semantic HTML5 elements
- Include proper accessibility attributes
- Validate HTML structure
- Use descriptive class names

```html
<!-- Good -->
<main class="dashboard-content">
  <section class="user-stats">
    <h2 class="stats-title">User Statistics</h2>
    <div class="stats-grid">
      <!-- Content -->
    </div>
  </section>
</main>

<!-- Avoid -->
<div class="div">
  <div class="title">User Statistics</div>
  <div class="grid">
    <!-- Content -->
  </div>
</div>
```

### CSS Guidelines
- Use CSS variables for theming
- Follow BEM methodology
- Mobile-first responsive design
- Keep specificity low

```css
/* Good */
.dashboard-card {
  background: var(--background-primary);
  border-radius: var(--border-radius);
  padding: var(--spacing-md);
}

.dashboard-card__title {
  color: var(--text-primary);
  font-size: var(--font-size-lg);
}

/* Avoid */
#main .container .card .title {
  background: #fff;
  border-radius: 8px;
  padding: 16px;
}
```

### JavaScript Guidelines
- Use vanilla JavaScript (no frameworks)
- Write clear, descriptive function names
- Add comments for complex logic
- Handle errors gracefully

```javascript
// Good
function validateUserInput(input) {
  if (!input || typeof input !== 'string') {
    throw new Error('Invalid input provided');
  }
  return input.trim().length > 0;
}

// Avoid
function v(i) {
  return i && i.length > 0;
}
```

### Commit Message Format

We follow [Conventional Commits](https://www.conventionalcommits.org/):

```
type(scope): description

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**
```bash
git commit -m "feat(dashboard): add user activity tracking"
git commit -m "fix(login): resolve authentication issue"
git commit -m "docs(readme): update installation instructions"
git commit -m "style(css): improve button hover states"
```

## 🧪 Testing Guidelines

### Manual Testing Checklist
Before submitting a PR, ensure:

- [ ] **Cross-browser compatibility**
  - Chrome (latest)
  - Firefox (latest)
  - Safari (latest)
  - Edge (latest)

- [ ] **Responsive design**
  - Mobile (320px+)
  - Tablet (768px+)
  - Desktop (1024px+)

- [ ] **Accessibility**
  - Keyboard navigation
  - Screen reader compatibility
  - Color contrast ratios
  - Focus indicators

- [ ] **Performance**
  - Page load times
  - Image optimization
  - Code minification

### Testing Tools
- **HTML Validator**: [W3C Validator](https://validator.w3.org/)
- **CSS Validator**: [W3C CSS Validator](https://jigsaw.w3.org/css-validator/)
- **Accessibility**: [axe DevTools](https://www.deque.com/axe/)
- **Performance**: [Lighthouse](https://developers.google.com/web/tools/lighthouse)

## 📝 Pull Request Guidelines

### Before Submitting
1. **Update documentation** if needed
2. **Add tests** for new features
3. **Test thoroughly** across browsers
4. **Follow the commit message format**
5. **Keep PRs focused** on one feature/fix

### PR Description Template
```markdown
## Description
Brief description of the changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Style/UI improvement
- [ ] Refactoring

## Testing
- [ ] Tested on Chrome
- [ ] Tested on Firefox
- [ ] Tested on Safari
- [ ] Tested on mobile
- [ ] Accessibility tested

## Screenshots (if applicable)
Add screenshots for UI changes

## Checklist
- [ ] Code follows project style
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No console errors
```

## 🐛 Bug Reports

### Before Reporting
1. Check if the issue is already reported
2. Try to reproduce the issue
3. Test on different browsers/devices

### Bug Report Template
```markdown
**Describe the bug**
Clear description of what the bug is

**To Reproduce**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected behavior**
What you expected to happen

**Screenshots**
If applicable, add screenshots

**Environment:**
- Browser: [e.g. Chrome 91]
- OS: [e.g. macOS 11.4]
- Device: [e.g. iPhone 12]

**Additional context**
Any other context about the problem
```

## 💡 Feature Requests

### Before Requesting
1. Check if the feature is already planned
2. Consider the project's scope and vision
3. Think about implementation complexity

### Feature Request Template
```markdown
**Is your feature request related to a problem?**
Description of the problem

**Describe the solution you'd like**
Clear description of what you want

**Describe alternatives you've considered**
Any alternative solutions

**Additional context**
Any other context or screenshots
```

## 📞 Getting Help

- **GitHub Issues**: For bugs and feature requests
- **GitHub Discussions**: For questions and community chat
- **Documentation**: Check the docs folder for detailed guides

## 🎉 Recognition

Contributors will be recognized in:
- Project README
- Release notes
- Community acknowledgments

Thank you for contributing to the Foz da Cova Dashboard project! 🌱 