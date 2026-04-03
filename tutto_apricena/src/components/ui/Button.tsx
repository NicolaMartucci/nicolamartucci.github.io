import React from 'react';
import { Link } from 'react-router-dom';

interface ButtonProps {
  children: React.ReactNode;
  href?: string;
  onClick?: () => void;
  variant?: 'primary' | 'secondary' | 'ghost' | 'outline';
  size?: 'sm' | 'md' | 'lg';
  className?: string;
  type?: 'button' | 'submit';
  disabled?: boolean;
  external?: boolean;
}

export default function Button({
  children, href, onClick, variant = 'primary', size = 'md',
  className = '', type = 'button', disabled = false, external = false
}: ButtonProps) {
  const base = 'inline-flex items-center gap-2 font-semibold rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';

  const variants = {
    primary: 'bg-[#E8A838] text-[#1A1A2E] hover:bg-[#C8881A] focus:ring-[#E8A838] shadow-md hover:shadow-lg',
    secondary: 'bg-[#1A1A2E] text-white hover:bg-[#2d2d4e] focus:ring-[#1A1A2E]',
    ghost: 'bg-white/10 text-white hover:bg-white/20 focus:ring-white',
    outline: 'border-2 border-[#1A1A2E] text-[#1A1A2E] hover:bg-[#1A1A2E] hover:text-white focus:ring-[#1A1A2E]',
  };

  const sizes = {
    sm: 'text-sm px-4 py-2',
    md: 'text-base px-6 py-3',
    lg: 'text-lg px-8 py-4',
  };

  const classes = `${base} ${variants[variant]} ${sizes[size]} ${disabled ? 'opacity-50 cursor-not-allowed' : ''} ${className}`;

  if (href) {
    if (external) {
      return <a href={href} className={classes} target="_blank" rel="noopener noreferrer">{children}</a>;
    }
    return <Link to={href} className={classes}>{children}</Link>;
  }

  return (
    <button type={type} onClick={onClick} className={classes} disabled={disabled}>
      {children}
    </button>
  );
}
