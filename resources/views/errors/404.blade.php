<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>404 | Page Not Found — Enterprise Edition</title>
    <!-- Professional Font: Inter + fallback -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 (solid & regular) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* professional grid & container */
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            isolation: isolate;
        }

        /* refined card with subtle border & modern shadow */
        .error-card {
            max-width: 620px;
            width: 100%;
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.02);
            padding: 2.75rem 2.5rem;
            transition: box-shadow 0.2s ease;
            border: 1px solid #eef2f6;
        }

        @media (max-width: 560px) {
            .error-card {
                padding: 2rem 1.5rem;
            }
        }

        /* subtle accent gradient elements */
        .accent-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f1f5f9;
            padding: 0.35rem 1rem 0.35rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.75rem;
        }

        .accent-badge i {
            color: #3b82f6;
            font-size: 0.7rem;
        }

        /* 404 illustration area — professional minimal */
        .error-code-visual {
            margin-bottom: 1.5rem;
        }

        .big-404 {
            font-size: 6rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 0.75rem;
            display: inline-block;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #ef4444;
            border-radius: 50%;
            margin-left: 0.5rem;
            vertical-align: middle;
            animation: pulse 1.8s infinite;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.5);}
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);}
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);}
        }

        h1 {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin: 0.5rem 0 1rem 0;
        }

        .description {
            color: #475569;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        /* actions grid — professional layout */
        .action-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2.25rem;
        }

        .btn-primary {
            flex: 1;
            background: #0f172a;
            color: white;
            border: none;
            padding: 0.85rem 1.2rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-primary i {
            font-size: 0.9rem;
            transition: transform 0.2s;
        }

        .btn-primary:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(15, 23, 42, 0.2);
        }

        .btn-primary:hover i {
            transform: translateX(-3px);
        }

        .btn-secondary {
            flex: 1;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 0.85rem 1.2rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 1px 1px rgba(0,0,0,0.02);
        }

        .btn-secondary i {
            font-size: 0.85rem;
            color: #5b6e8c;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px -6px rgba(0, 0, 0, 0.08);
        }

        /* quick links section — clean & pro */
        .quick-links {
            background: #f9fbfd;
            border-radius: 1.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.8rem;
            border: 1px solid #edf2f7;
        }

        .quick-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #5b6e8c;
            margin-bottom: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .link-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .quick-link {
            font-size: 0.85rem;
            font-weight: 500;
            color: #2563eb;
            background: transparent;
            padding: 0.3rem 0;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
            text-decoration: none;
            border-bottom: 1px dashed #cbd5e1;
        }

        .quick-link i {
            font-size: 0.7rem;
            color: #5b6e8c;
        }

        .quick-link:hover {
            color: #1d4ed8;
            border-bottom-color: #3b82f6;
        }

        /* support bar (professional minimal) */
        .support-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.8rem;
            border-top: 1px solid #eef2f6;
            padding-top: 1.5rem;
            margin-top: 0.5rem;
        }

        .support-text {
            font-size: 0.8rem;
            color: #5b6e8c;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .support-text i {
            color: #3b82f6;
            font-size: 0.9rem;
        }

        .contact-link {
            background: none;
            border: none;
            font-weight: 600;
            font-size: 0.8rem;
            color: #0f172a;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.2s;
            text-decoration: none;
        }

        .contact-link:hover {
            color: #2563eb;
        }

        footer {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.7rem;
            color: #94a3b8;
        }

        /* subtle decorative wave (bottom) */
        .bg-subtle {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 180px;
            background: linear-gradient(135deg, rgba(59,130,246,0.02) 0%, rgba(139,92,246,0.02) 100%);
            pointer-events: none;
            z-index: -1;
        }

        @media (max-width: 480px) {
            .big-404 {
                font-size: 4.2rem;
            }
            h1 {
                font-size: 1.6rem;
            }
            .action-grid {
                flex-direction: column;
            }
            .btn-primary, .btn-secondary {
                width: 100%;
            }
        }

        /* interactive micro-interactions */
        .btn-primary:active, .btn-secondary:active {
            transform: translateY(1px);
        }

        /* custom focus rings for accessibility */
        a:focus-visible, button:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
            border-radius: 40px;
        }
    </style>
</head>
<body>

<div class="bg-subtle"></div>

<div class="error-container">
    <div class="error-card">
        
        <!-- professional status header -->
        <div class="accent-badge">
            <i class="fas fa-map-signs"></i>
            <span>ERROR 404 · RESOURCE NOT FOUND</span>
        </div>

        <!-- clean 404 visual without overkill -->
        <div class="error-code-visual">
            <div class="big-404">
                404
                <span class="status-dot"></span>
            </div>
        </div>

        <h1>Page unavailable</h1>
        <p class="description">
            The requested URL could not be located on this server. It may have been moved, deleted, or the address might contain a typo. Please verify the link or return to the dashboard.
        </p>

        <!-- professional cta row -->
        <div class="action-grid">
            <a href="{{ url('/') }}" class="btn-primary">
                <i class="fas fa-arrow-left"></i> 
                Back to Home
            </a>
            <button onclick="history.back()" class="btn-secondary">
                <i class="fas fa-chevron-left"></i> 
                Previous page
            </button>
        </div>

        <!-- smart quick navigation (enterprise style) -->
        <div class="quick-links">
            <div class="quick-title">
                <i class="fas fa-life-ring"></i>
                <span>QUASSAR·NAVIGATION</span>
            </div>
            <div class="link-group">
                <a href="{{ url('/dashboard') }}" class="quick-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ url('/support') }}" class="quick-link">
                    <i class="fas fa-headset"></i> Help Center
                </a>
                <a href="{{ url('/documentation') }}" class="quick-link">
                    <i class="fas fa-book"></i> Documentation
                </a>
                <a href="{{ url('/contact') }}" class="quick-link">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>
        </div>

        <!-- support & enterprise-grade info -->
        <div class="support-meta">
            <div class="support-text">
                <i class="fas fa-shield-alt"></i>
                <span>System status: operational</span>
            </div>
            <div>
                <button id="supportTriggerBtn" class="contact-link">
                    <i class="fas fa-ticket-alt"></i> Report issue
                </button>
            </div>
        </div>

        <footer>
            <i class="far fa-clock"></i> 2025 · Enterprise Console — version 2.4.1
        </footer>
    </div>
</div>

<script>
    // Enhance professionalism: dynamic report issue with feedback simulation,
    // also add fallback for support links to mimic real enterprise touch.
    const supportBtn = document.getElementById('supportTriggerBtn');
    if (supportBtn) {
        supportBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // professional behavior: show a subtle toast-like alert, but in a modern non-intrusive way.
            // Since we don't have a full framework, we create a light temporary notification that vanishes.
            const notification = document.createElement('div');
            notification.innerText = '🔧 Support request logged. Our team will respond within 2 hours.';
            notification.style.position = 'fixed';
            notification.style.bottom = '24px';
            notification.style.left = '50%';
            notification.style.transform = 'translateX(-50%)';
            notification.style.backgroundColor = '#0f172a';
            notification.style.color = '#f1f5f9';
            notification.style.padding = '10px 20px';
            notification.style.borderRadius = '40px';
            notification.style.fontSize = '0.8rem';
            notification.style.fontWeight = '500';
            notification.style.fontFamily = "'Inter', sans-serif";
            notification.style.zIndex = '999';
            notification.style.boxShadow = '0 10px 20px -6px rgba(0,0,0,0.2)';
            notification.style.backdropFilter = 'blur(8px)';
            notification.style.border = '1px solid rgba(255,255,255,0.1)';
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s';
                setTimeout(() => notification.remove(), 400);
            }, 2800);
            
            // optionally, you could also log to console for debug (professional)
            console.info('[Support] User reported 404 page issue from location: ' + window.location.href);
        });
    }

    // Additional graceful handling for all quick links (they inherit from url() which works in Laravel/any)
    // For demo: ensure that secondary button also provides smooth micro-interaction.
    const allQuickLinks = document.querySelectorAll('.quick-link');
    allQuickLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            // Just normal navigation, but we can add a tiny loading indicator effect?
            // Not mandatory, maintain default behavior. Keep it sleek.
        });
    });

    // Optional: if any internal route fails (due to missing href) but we keep them safe
    // Also add a small easter egg: professional console greeting for devs
    console.log("%c✨ Enterprise 404 | Enhanced UI — All systems stable.", "color: #3b82f6; font-weight: 500; font-size: 12px;");
    
    // Additional a11y: make go-back button friendly with history fallback
    const goBackButtons = document.querySelectorAll('.btn-secondary');
    if (goBackButtons.length) {
        // already handled via onclick history.back, but we ensure if history empty, fallback to home.
        const backBtnElem = document.querySelector('.btn-secondary');
        if (backBtnElem && !backBtnElem.hasAttribute('data-listener')) {
            const originalClick = backBtnElem.onclick;
            backBtnElem.setAttribute('data-listener', 'true');
            backBtnElem.addEventListener('click', (e) => {
                if (window.history.length <= 1) {
                    e.preventDefault();
                    window.location.href = "{{ url('/') }}";
                } else {
                    history.back();
                }
            });
        }
    }
    
    // Fix for the potential fallback: manually rewrite inline onclick to preserve fallback.
    // Because above button uses onclick="history.back()" so we override safely.
    window.addEventListener('DOMContentLoaded', () => {
        const backBtns = document.querySelectorAll('button[onclick="history.back()"]');
        backBtns.forEach(btn => {
            const oldOnclick = btn.getAttribute('onclick');
            if (oldOnclick && oldOnclick.includes('history.back')) {
                btn.removeAttribute('onclick');
                btn.addEventListener('click', (e) => {
                    if (window.history.length > 1) {
                        history.back();
                    } else {
                        window.location.href = "{{ url('/') }}";
                    }
                });
            }
        });
        
        // Preload hover effect refinement: make all cards interactive subtle
        const card = document.querySelector('.error-card');
        if(card) {
            card.addEventListener('mouseenter', () => {
                card.style.transition = 'box-shadow 0.25s ease';
                card.style.boxShadow = '0 25px 40px -16px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0,0,0,0.02)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.boxShadow = '0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.02)';
            });
        }
    });
</script>
</body>
</html>