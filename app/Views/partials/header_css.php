        /* ヘッダー */
        .header {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            gap: 35px;
            align-items: center;
        }

        .logo-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 20px;
            font-weight: 600;
            color: #2c1810;
            letter-spacing: 3px;
            text-decoration: none;
        }

        .header-right {
            display: flex;
            gap: 35px;
            align-items: center;
        }

        .nav {
            display: flex;
            gap: 35px;
            list-style: none;
        }

        .nav a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            font-weight: 400;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: #8b7355;
        }

        .header-icon {
            width: 35px;
            height: 35px;
            background: #2c1810;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .header-icon:hover {
            background: #8b7355;
        }

        /* ハンバーガーメニューボタン */
        .menu-btn {
            display: none;
            flex-direction: column;
            justify-content: center;
            width: 30px;
            height: 30px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
        }

        .menu-line {
            display: block;
            width: 25px;
            height: 3px;
            background: #333;
            margin: 3px 0;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .menu-btn.is-active .menu-line {
            background: #fff;
        }

        .menu-btn.is-active .menu-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .menu-btn.is-active .menu-line:nth-child(2) {
            opacity: 0;
        }

        .menu-btn.is-active .menu-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* レスポンシブ */
        @media (max-width: 1024px) {
            .header-left .nav,
            .header-right .nav {
                display: none;
            }

            .logo-center {
                position: static;
                transform: none;
            }

            .header-icon {
                margin-left: auto;
            }

            .menu-btn {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 15px 20px;
            }

            .header-left .nav,
            .header-right .nav {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(25, 68, 142, 0.95);
                backdrop-filter: blur(10px);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 30px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .nav.is-open {
                transform: translateX(0);
            }

            .nav a {
                font-size: 20px;
                font-weight: 600;
                padding: 15px 0;
                color: #fff;
            }

            body.menu-open {
                overflow: hidden;
            }
        }
