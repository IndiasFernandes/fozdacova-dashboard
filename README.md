# Foz da Cova Dashboard System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Status: Development](https://img.shields.io/badge/Status-Development-orange.svg)](https://github.com/IndiasFernandes/fozdacova-dashboard)
[![Frontend: HTML/CSS/JS](https://img.shields.io/badge/Frontend-HTML%2FCSS%2FJS-blue.svg)](https://developer.mozilla.org/en-US/docs/Web/HTML)

A comprehensive dashboard system for the Foz da Cova community land project, providing role-based interfaces for administrators, stewards, and WWOOFers to manage projects, track activities, and coordinate community living.

## 🌱 About Foz da Cova

Foz da Cova is a regenerative living project in central Portugal focused on community care, sustainable practices, and meaningful participation. This dashboard system supports three distinct user roles with appropriate permissions and interfaces.

## ✨ Features

### 🏠 Public Area
- **Landing page** with project overview and community stats
- **About page** with community story and values
- **Projects showcase** with current opportunities
- **Calendar** with community events and work days
- **Contact form** for inquiries and applications
- **Join page** with role descriptions and application process

### 👥 Role-Based Dashboards

#### **Admin Dashboard**
- Full system oversight and financial control
- Quick actions grid for efficient management
- Current residents tracking with contact information
- Expense tracking and financial reports
- Strategic decision making interface

#### **Stewart Dashboard**
- Project coordination and team management
- Progress tracking with milestone logging
- Resource coordination and material requests
- Team leadership with clear assignment tools
- Communication with WWOOFers and other stewards

#### **WWOOFer Dashboard**
- Project participation and joining available work
- Activity logging and personal contribution tracking
- Learning resources and skill development
- Community engagement and schedule viewing
- Personal progress tracking and reflection
- Task management for ongoing responsibilities

## 🛠 Technology Stack

- **Frontend**: Pure HTML, CSS, JavaScript
- **Design**: Mobile-first responsive design
- **Architecture**: Role-based access control
- **Performance**: Lightweight, fast-loading pages
- **Accessibility**: WCAG compliant design

## 📁 Project Structure

```
fozdacova-dashboard/
├── public/                 # Public website pages
│   ├── index.html         # Landing page
│   ├── about.html         # About the community
│   ├── projects.html      # Project showcase
│   ├── calendar.html      # Community calendar
│   ├── contact.html       # Contact form
│   └── join.html          # Application page
├── dashboards/            # Role-based dashboards
│   ├── admin/            # Admin interface
│   ├── stewart/          # Stewart interface
│   ├── wwoofer/          # WWOOFer interface
│   ├── shared/           # Shared components
│   └── login.html        # Authentication
├── docs/                 # Documentation
├── data/                 # Data schemas and examples
└── index.html            # Root redirect
```

## 🚀 Quick Start

### Prerequisites
- Modern web browser
- Local web server (optional, for development)

### Installation
1. Clone the repository:
```bash
git clone https://github.com/IndiasFernandes/fozdacova-dashboard.git
cd fozdacova-dashboard
```

2. Open in your browser:
```bash
# Using Python (if installed)
python -m http.server 8000

# Using Node.js (if installed)
npx serve .

# Or simply open index.html in your browser
```

3. Navigate to the desired interface:
- **Public**: `public/index.html`
- **Admin**: `dashboards/admin/index.html`
- **Stewart**: `dashboards/stewart/index.html`
- **WWOOFer**: `dashboards/wwoofer/index.html`

## 🎨 Design System

### Color Palette
- **Primary Green**: `#2d5a27` (Buttons, accents, headers)
- **Secondary Earth**: `#8b7355` (Backgrounds, sections)
- **Sky Blue**: `#87ceeb` (Highlights, calendar)
- **Warm Sand**: `#d4a574` (Callouts, hover states)

### Typography
- **Primary Font**: Inter (Clean, modern sans-serif)
- **Secondary Font**: Georgia (For emphasis and quotes)

### Layout System
- **CSS Grid** for page layouts
- **Flexbox** for components
- **Mobile-first** responsive design
- **Consistent spacing** using CSS variables

## 📊 Database Schema

The system is designed to work with SQLite and includes schemas for:
- **Projects**: Community initiatives and tasks
- **Events**: Calendar events and work days
- **Users**: Role-based user management
- **Activities**: Work logging and tracking
- **Expenses**: Financial tracking
- **Tasks**: Ongoing responsibilities

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

### Development Workflow
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes following the coding standards
4. Test your changes thoroughly
5. Commit with clear messages: `git commit -m "Add amazing feature"`
6. Push to your branch: `git push origin feature/amazing-feature`
7. Open a Pull Request

### Coding Standards
- **HTML**: Semantic markup, accessibility attributes
- **CSS**: BEM methodology, CSS variables, mobile-first
- **JavaScript**: Vanilla JS, clear function names, comments
- **Commits**: Conventional commits format

### Commit Message Format
```
type(scope): description

[optional body]

[optional footer]
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

### Pull Request Guidelines
- Clear description of changes
- Screenshots for UI changes
- Test coverage for new features
- Follow existing code style

## 📝 Documentation

- **[Concept Document](FOZ_DA_COVA_CONCEPT.md)**: Project vision and design principles
- **[Structure Document](FOZ_DA_COVA_STRUCTURE.md)**: Technical architecture and database schema
- **[Dashboard Designs](FOZ_DA_COVA_DASHBOARD_DESIGNS.md)**: UI/UX design patterns and layouts

## 🧪 Testing

### Manual Testing Checklist
- [ ] All pages load correctly
- [ ] Responsive design works on mobile/tablet/desktop
- [ ] Navigation works across all interfaces
- [ ] Forms submit and validate properly
- [ ] Role-based access control functions
- [ ] Cross-browser compatibility (Chrome, Firefox, Safari, Edge)

### Performance Testing
- Page load times under 2 seconds
- Mobile performance optimization
- Image optimization and lazy loading

## 📈 Roadmap

### Phase 1: Foundation ✅
- [x] Basic HTML structure
- [x] CSS Grid/Flexbox system
- [x] Role-based dashboards
- [x] Mobile-first responsive design

### Phase 2: Enhancement 🚧
- [ ] Database integration
- [ ] User authentication
- [ ] Real-time updates
- [ ] Advanced form validation

### Phase 3: Advanced Features 📋
- [ ] PWA implementation
- [ ] Offline functionality
- [ ] Advanced analytics
- [ ] API development

### Phase 4: Community Features 📋
- [ ] Discussion forums
- [ ] File sharing
- [ ] Advanced calendar features
- [ ] Mobile app companion

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Foz da Cova Community** for inspiration and feedback
- **Open source community** for tools and libraries
- **Contributors** who help improve this project

## 📞 Contact

- **Project Link**: [https://github.com/IndiasFernandes/fozdacova-dashboard](https://github.com/IndiasFernandes/fozdacova-dashboard)
- **Community Website**: [Foz da Cova](https://fozdacova.com)

---

*Building a sustainable future together through technology and community.* 🌱 