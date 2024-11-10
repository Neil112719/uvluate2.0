import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const LoginPage = () => {
    const [idNumber, setIdNumber] = useState('');
    const [password, setPassword] = useState('');
    const [otp, setOtp] = useState('');
    const [showOtpOverlay, setShowOtpOverlay] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');
    const [userType, setUserType] = useState(null); // Track user type for redirection
    const navigate = useNavigate();

    const handleLogin = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post(
                'http://localhost:8000/login.php',
                { id_number: idNumber, password },
                { headers: { 'Content-Type': 'application/json' }, withCredentials: true }
            );

            if (response.data.status === 'otp_required') {
                setUserType(response.data.usertype); // Save user type for later redirection
                setShowOtpOverlay(true); // Show OTP overlay
            } else {
                setErrorMessage(response.data.message);
            }
        } catch (error) {
            setErrorMessage("An error occurred during login.");
        }
    };

    const handleOtpSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post(
                'http://localhost:8000/verify_otp.php',
                { id_number: idNumber, otp },
                { headers: { 'Content-Type': 'application/json' }, withCredentials: true }
            );

            if (response.data.status === 'success') {
                // Save authentication status and user type
                localStorage.setItem('isAuthenticated', true);
                localStorage.setItem('userType', userType);

                // Redirect based on user type
                switch (userType) {
                    case 1:
                        navigate('/admin');
                        break;
                    case 2:
                        navigate('/dean');
                        break;
                    case 3:
                        navigate('/coordinator');
                        break;
                    case 4:
                        navigate('/student');
                        break;
                    default:
                        setErrorMessage('Unknown user type');
                }
            } else {
                setErrorMessage(response.data.message);
            }
        } catch (error) {
            setErrorMessage("An error occurred during OTP verification.");
        }
    };

    return (
        <div className="login-page">
            <form onSubmit={handleLogin}>
                <h2>Login</h2>
                <input
                    type="text"
                    placeholder="ID Number"
                    value={idNumber}
                    onChange={(e) => setIdNumber(e.target.value)}
                    required
                />
                <input
                    type="password"
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                />
                <button type="submit">Login</button>
                {errorMessage && <p className="error">{errorMessage}</p>}
            </form>

            {/* OTP Overlay */}
            {showOtpOverlay && (
                <div className="otp-overlay">
                    <form onSubmit={handleOtpSubmit}>
                        <h2>Enter OTP</h2>
                        <input
                            type="text"
                            placeholder="OTP"
                            value={otp}
                            onChange={(e) => setOtp(e.target.value)}
                            required
                        />
                        <button type="submit">Verify OTP</button>
                    </form>
                </div>
            )}
        </div>
    );
};

export default LoginPage;
