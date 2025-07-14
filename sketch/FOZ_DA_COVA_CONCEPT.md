# FOZ DA COVA – Concept, Vibe & Visual Direction

## 🌱 Objective
Foz da Cova is a living land project focused on stewardship, community care, and regenerative living. The goal of the website and system is to:
- Create an **easy-to-use, lightweight platform** for visitors, administrators, stewards, and WWOOFers.
- Encourage **participation and ownership** by allowing stewards to coordinate projects and WWOOFers to contribute meaningfully.
- Provide **accountability, clarity, and beauty** without complexity.
- Support **three distinct user roles** with appropriate permissions and interfaces.

## ✨ Vibe & Essence
- **Nature-centered, grounded, regenerative.**
- Feels **inviting, warm, and minimal**—no excess, no overwhelm.
- A digital reflection of the **care, simplicity, and respect for land and people** that the project stands for.
- **Community-focused** with clear role definitions and responsibilities.

## 🎨 Visual Identity

### Primary Colors
| Color Name       | Hex Code  | Usage                                |
|------------------|-----------|---------------------------------------|
| Forest Green     | #2d5a27   | Buttons, accents, headers            |
| Soft Earth Brown | #8b7355   | Backgrounds, sections, secondary UI  |
| Sky Blue         | #87ceeb   | Highlights, calendar, small details  |

### Neutral Colors
| Color Name       | Hex Code  | Usage                                |
|------------------|-----------|---------------------------------------|
| White            | #ffffff   | Main backgrounds                     |
| Light Gray       | #f8f9fa   | Secondary backgrounds, cards         |
| Mid Gray         | #6c757d   | Body text, icons                     |
| Dark Gray        | #343a40   | Headers, footers, bold text          |

### Accent Colors
| Color Name       | Hex Code  | Usage                                |
|------------------|-----------|---------------------------------------|
| Warm Sand        | #d4a574   | Callouts, hover states, visual warmth|
| Olive Accent     | #4a7c59   | Optional backgrounds, small icons    |

## 🧭 Design Direction (for Cursor to Follow)

1. **Minimal and fast-loading:** No heavy frameworks. Pure HTML/CSS with optional tiny JavaScript if strictly necessary.
2. **Mobile-first:** Design for phones first, then scale up.
3. **Visual Calm:** Lots of white/neutral space, soft earthy tones, nothing aggressive or over-saturated.
4. **Natural Flow:** Rounded buttons, soft shadows, minimal hard lines.
5. **Readable & Friendly Typography:** Use `Inter` or another clean sans-serif.
6. **Organic Visual Cues:** Circles, soft curves, plant-inspired details.
7. **Role-Based Design:** Each user type gets an interface optimized for their responsibilities.

## 👥 User Roles & Experience

### Admin Experience
- **Full system oversight** with financial control
- **Quick actions grid** for efficient management
- **Current residents** tracking with contact information
- **Expense tracking** and financial reports
- **Strategic decision making** interface

### Stewart Experience
- **Project coordination** and team management
- **Progress tracking** with milestone logging
- **Resource coordination** and material requests
- **Team leadership** with clear assignment tools
- **Communication** with WWOOFers and other stewards

### WWOOFer Experience
- **Project participation** and joining available work (water systems, plant care, land maintenance)
- **Activity logging** and personal contribution tracking
- **Learning resources** and skill development
- **Community engagement** and schedule viewing
- **Personal progress** tracking and reflection
- **Task management** for ongoing responsibilities (plant watering, land trimming)

## 🛠 Technical Guiding Principles

- Use **CSS Grid** for page layouts and **Flexbox** for components.
- Stick to **small CSS files** using CSS variables for theming.
- All dynamic content should load fast from **SQLite** with minimal page weight.
- Prioritize **clarity over complexity** in every decision.
- **Role-based access control** with appropriate permissions.
- **Mobile-first responsive design** for field work.
- **Lightweight authentication** system.

## 📱 Interface Design Principles

### Admin Dashboard
- **Overview-focused** with quick access to all areas
- **Financial transparency** and expense tracking
- **User management** and system configuration
- **Strategic planning** tools and reports

### Stewart Dashboard
- **Project-focused** with coordination tools
- **Team management** and task assignment
- **Progress tracking** and milestone logging
- **Resource requests** and communication

### WWOOFer Dashboard
- **Participation-focused** with available projects
- **Learning resources** and skill development
- **Personal activity** logging and reflection
- **Community engagement** and schedule viewing

## 🎯 Content Strategy

### Public Area
- **Inviting and informative** for potential participants
- **Clear application process** and requirements
- **Project showcase** with current opportunities
- **Community values** and land description

### Dashboard Areas
- **Role-appropriate information** and actions
- **Progressive disclosure** based on permissions
- **Clear navigation** and task completion
- **Community connection** and communication

## 🔄 Workflow Design

### Project Lifecycle
1. **Admin creates project** with requirements and resources
2. **Stewart coordinates** project with team assignments
3. **WWOOFers join** and participate in projects
4. **Activities are logged** by all participants
5. **Progress is tracked** and reported
6. **Admin reviews** and approves completion

### Communication Flow
- **Monday calls** at 5 PM Portugal time
- **Project updates** and progress tracking
- **Resource requests** from Stewart to Admin
- **Community engagement** and learning sharing

## 📊 Performance Requirements

### Loading Speed
- **Under 2 seconds** for initial page load
- **Progressive enhancement** for better user experience
- **Optimized images** and minimal assets
- **Efficient database queries** with proper indexing

### Mobile Performance
- **Touch-friendly** interactions (44px minimum)
- **Fast loading** on mobile networks
- **Offline capability** for basic functionality
- **Responsive design** for all screen sizes

## 🎨 Visual Consistency

All dashboards (Admin, Stewart, WWOOFer) now use a unified CSS for grid layouts, dashboard cards, and link styles. All visual and UX elements are consistent across all dashboards, ensuring a seamless experience for all roles.

### Across All Interfaces
- **Same color palette** and typography
- **Consistent component library** (buttons, cards, forms)
- **Unified navigation patterns** and interactions
- **Role-appropriate complexity** and information density

### Brand Elements
- **Foz da Cova logo** and identity
- **Natural imagery** and organic shapes
- **Community-focused** messaging and tone
- **Regenerative living** values and principles

---

*This document serves as the north star for all design and development decisions in the Foz da Cova project, ensuring consistency across the public area and all three dashboard interfaces.*
