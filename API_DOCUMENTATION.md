# Qavyx Backend API Documentation

Welcome to the **Qavyx Laravel REST API Backend**. This backend provides comprehensive APIs for modern React frontends and an SEO-focused Admin Panel.

---

## 1. Authentication & Credentials

### Default Admin Credentials
- **Username:** `admin`
- **Email:** `admin@Qavyx.com`
- **Password:** `Qavyx@2026`

All protected admin endpoints require a Bearer token in the `Authorization` header:
```http
Authorization: Bearer <your_token>
```

---

## 2. API Endpoints Overview

### A. Authentication
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `POST` | `/api/admin/login` | Login with username/email & password | No |
| `POST` | `/api/admin/logout` | Revoke current token | Yes |
| `GET` | `/api/admin/me` | Fetch authenticated admin details | Yes |
| `PUT` | `/api/admin/profile` | Update profile info or password | Yes |

#### Login Request Example:
```json
POST /api/admin/login
Content-Type: application/json

{
  "username": "admin",
  "password": "Qavyx@2026"
}
```

#### Login Response:
```json
{
  "success": true,
  "message": "Login successful",
  "token": "1|abcdef123456...",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "Qavyx Admin",
    "username": "admin",
    "email": "admin@Qavyx.com",
    "role": "admin",
    "avatar": null
  }
}
```

---

### B. Admin Dashboard
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/admin/dashboard` | Overview metrics (posts, views, SEO pages, leads) | Yes |

---

### C. Admin Blog & Post Management (with SEO)
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/admin/blogs` | List all blogs (draft, published, archived) with search/filter | Yes |
| `POST` | `/api/admin/blogs` | Create a new blog post with complete SEO metadata | Yes |
| `GET` | `/api/admin/blogs/{id}` | Get blog post details | Yes |
| `PUT` | `/api/admin/blogs/{id}` | Update blog post & SEO fields | Yes |
| `DELETE` | `/api/admin/blogs/{id}` | Delete blog post | Yes |
| `POST` | `/api/admin/blogs/{id}/toggle-publish` | Quick toggle publish / draft | Yes |

#### Blog Post Fields (Payload):
```json
{
  "title": "Mastering SEO in Modern React Applications",
  "slug": "mastering-seo-in-modern-react-applications",
  "excerpt": "A short summary of the post...",
  "content": "<p>Full blog content in HTML or Markdown...</p>",
  "featured_image": "http://localhost/Qavyx-back/storage/uploads/example.jpg",
  "featured_image_alt": "Alt text for SEO",
  "featured_image_caption": "Caption",
  "category_id": 1,
  "tags": ["React", "SEO", "Web Development"],
  "status": "published",
  "is_featured": true,
  "meta_title": "Custom Meta Title for Google SERP",
  "meta_description": "Search engine meta description (under 160 characters)",
  "meta_keywords": "react seo, single page application, meta tags",
  "canonical_url": "https://example.com/blog/mastering-seo-in-modern-react-applications",
  "robots_index": true,
  "robots_follow": true,
  "og_title": "Social Sharing Title",
  "og_description": "Social Sharing Description",
  "og_image": "http://localhost/Qavyx-back/storage/uploads/og-example.jpg",
  "og_type": "article",
  "twitter_card": "summary_large_image",
  "twitter_title": "Twitter Card Title",
  "twitter_description": "Twitter Card Description",
  "twitter_image": "http://localhost/Qavyx-back/storage/uploads/twitter-example.jpg",
  "schema_markup": {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "Post Title"
  }
}
```

---

### D. Universal Page SEO Management (for ANY React Route)
Manage meta titles, meta descriptions, OpenGraph tags, Twitter cards, and Schema.org structured data for routes like `/`, `/about`, `/services`, `/portfolio`, `/contact`, etc.

| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/admin/seo-pages` | List all configured page SEO records | Yes |
| `POST` | `/api/admin/seo-pages` | Add SEO metadata for a route/path | Yes |
| `GET` | `/api/admin/seo-pages/{id}` | Get single page SEO config | Yes |
| `PUT` | `/api/admin/seo-pages/{id}` | Update page SEO config | Yes |
| `DELETE` | `/api/admin/seo-pages/{id}` | Delete page SEO config | Yes |

---

### E. Global SEO Settings
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/admin/seo-settings` | Get site-wide SEO settings | Yes |
| `PUT` | `/api/admin/seo-settings` | Update site-wide SEO defaults, verification codes & scripts | Yes |

Supported settings:
- `site_name`
- `title_separator` (` | `, ` - `)
- `default_meta_title`
- `default_meta_description`
- `default_meta_keywords`
- `default_og_image`
- `twitter_handle`
- `google_site_verification`
- `bing_site_verification`
- `custom_head_scripts` (Google Tag Manager, Google Analytics, etc.)
- `custom_body_scripts`
- `sitemap_enabled`

---

### F. Categories Management
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/admin/categories` | List all categories with post counts | Yes |
| `POST` | `/api/admin/categories` | Create category | Yes |
| `GET` | `/api/admin/categories/{id}`| Get category | Yes |
| `PUT` | `/api/admin/categories/{id}`| Update category | Yes |
| `DELETE` | `/api/admin/categories/{id}`| Delete category | Yes |

---

### G. Media / Asset Uploads
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/admin/media` | List uploaded files | Yes |
| `POST` | `/api/admin/media/upload` | Upload image (`multipart/form-data`) | Yes |
| `DELETE` | `/api/admin/media/{id}` | Delete image | Yes |

---

### H. Contact Inquiries & Leads
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/admin/contacts` | List customer inquiries | Yes |
| `PATCH` | `/api/admin/contacts/{id}/read` | Mark message as read | Yes |
| `DELETE` | `/api/admin/contacts/{id}` | Delete inquiry | Yes |

---

## 3. Public APIs for the React Frontend

### Fetch Page SEO for any Route:
```http
GET /api/seo?path=/about
```
Returns:
```json
{
  "success": true,
  "data": {
    "path": "/about",
    "title": "About Qavyx - Innovators in High-Performance Digital Solutions | Qavyx",
    "description": "Learn about the mission, team, and technology stack powering Qavyx.",
    "keywords": "about Qavyx, digital agency, tech team, react specialists",
    "canonical": "http://localhost/Qavyx-back/about",
    "robots": "index, follow",
    "openGraph": {
      "site_name": "Qavyx",
      "title": "About Qavyx - Our Vision, Values & Technology",
      "description": "Discover how Qavyx helps startups and enterprises scale through engineering excellence.",
      "image": "https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200",
      "type": "website"
    },
    "twitter": {
      "card": "summary_large_image",
      "title": "About Qavyx - Our Vision, Values & Technology",
      "description": "Discover how Qavyx helps startups and enterprises scale through engineering excellence.",
      "image": "https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200",
      "site": "@Qavyx"
    },
    "schema": {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Qavyx"
    },
    "verification": {
      "google": "...",
      "bing": "..."
    },
    "scripts": {
      "head": null,
      "body": null
    }
  }
}
```

### React Integration Hook (e.g., using `react-helmet-async`):
```jsx
import { useEffect, useState } from 'react';
import { Helmet } from 'react-helmet-async';
import { useLocation } from 'react-router-dom';

export function usePageSeo() {
  const location = useLocation();
  const [seo, setSeo] = useState(null);

  useEffect(() => {
    fetch(`http://localhost/Qavyx-back/api/seo?path=${location.pathname}`)
      .then(res => res.json())
      .then(res => {
        if (res.success) setSeo(res.data);
      });
  }, [location.pathname]);

  if (!seo) return null;

  return (
    <Helmet>
      <title>{seo.title}</title>
      <meta name="description" content={seo.description} />
      {seo.keywords && <meta name="keywords" content={seo.keywords} />}
      <meta name="robots" content={seo.robots} />
      {seo.canonical && <link rel="canonical" href={seo.canonical} />}
      
      {/* OpenGraph */}
      <meta property="og:title" content={seo.openGraph.title} />
      <meta property="og:description" content={seo.openGraph.description} />
      <meta property="og:image" content={seo.openGraph.image} />
      <meta property="og:type" content={seo.openGraph.type} />

      {/* Twitter */}
      <meta name="twitter:card" content={seo.twitter.card} />
      <meta name="twitter:title" content={seo.twitter.title} />
      <meta name="twitter:description" content={seo.twitter.description} />
      <meta name="twitter:image" content={seo.twitter.image} />

      {/* JSON-LD Schema */}
      {seo.schema && (
        <script type="application/ld+json">
          {JSON.stringify(seo.schema)}
        </script>
      )}
    </Helmet>
  );
}
```

### Public Blog Endpoints:
- `GET /api/blogs`: Published blogs (filters: `?search=...`, `?category=slug`, `?tag=...`, `?featured=1`, `?page=1&per_page=9`)
- `GET /api/blogs/featured`: Top featured posts
- `GET /api/blogs/recent`: Latest 5 posts
- `GET /api/blogs/{slug}`: Post content + formatted SEO object + related articles + view increment
- `GET /api/categories`: Category list with active post counts
- `GET /api/categories/{slug}`: Category detail + its posts

### Search Engines & Sitemaps:
- `GET /api/sitemap.xml`: Dynamic XML sitemap
- `GET /api/sitemap`: JSON sitemap

### Public Contact Form:
- `POST /api/contact`
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "phone": "+1234567890",
  "subject": "Inquiry",
  "message": "Hello, I would like to enquire about your services."
}
```

---

### Public Team & Leadership API (Directors Section):
- `GET /api/team` (or `GET /api/team-members`)
Returns all active team members sorted by display order.

#### Response Example:
```json
{
  "success": true,
  "count": 5,
  "data": [
    {
      "id": 1,
      "name": "Zack Hill",
      "position": "Founder & CEO",
      "email": "zack@qavyx.com",
      "photo": "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800",
      "photo_url": "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800",
      "order": 1,
      "is_active": true,
      "created_at": "2026-11-09T12:00:00.000000Z",
      "updated_at": "2026-11-09T12:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Arlton Lowry",
      "position": "Partner & CCO",
      "email": "arlton@qavyx.com",
      "photo": "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800",
      "photo_url": "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800",
      "order": 2,
      "is_active": true
    },
    {
      "id": 3,
      "name": "Sarah Devine",
      "position": "Director of Projects",
      "email": "sarah@qavyx.com",
      "photo": "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800",
      "photo_url": "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800",
      "order": 3,
      "is_active": true
    },
    {
      "id": 4,
      "name": "Will Carter",
      "position": "Director of Accounts",
      "email": "will@qavyx.com",
      "photo": "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800",
      "photo_url": "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800",
      "order": 4,
      "is_active": true
    },
    {
      "id": 5,
      "name": "Calvin Bramlett",
      "position": "Creative & Design Director",
      "email": "calvin@qavyx.com",
      "photo": "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800",
      "photo_url": "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800",
      "order": 5,
      "is_active": true
    }
  ]
}
```

#### React Frontend Component Example:
```jsx
import React, { useEffect, useState } from 'react';

export function TeamSection() {
  const [team, setTeam] = useState([]);

  useEffect(() => {
    fetch('https://qavixbackend-production.up.railway.app/api/team')
      .then(res => res.json())
      .then(data => {
        if (data.success) setTeam(data.data);
      });
  }, []);

  return (
    <section className="bg-black py-20 px-6">
      <div className="max-w-7xl mx-auto">
        <h2 className="text-3xl font-bold text-white mb-12">Leadership & Directors</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          {team.map(member => (
            <div key={member.id} className="relative group overflow-hidden rounded-t-[50px] bg-[#0c0d0c] border border-white/10 p-4">
              <div className="aspect-[3/4] overflow-hidden rounded-t-[40px] mb-4">
                <img
                  src={member.photo_url}
                  alt={member.name}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
              </div>
              <h3 className="text-lg font-bold text-white">{member.name}</h3>
              <p className="text-sm text-gray-400">{member.position}</p>
              {member.email && (
                <a href={`mailto:${member.email}`} className="text-xs text-green-500 hover:underline block mt-1">
                  {member.email}
                </a>
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
```


