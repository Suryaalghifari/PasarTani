const request = require('supertest');
const app = require('../index'); // atau path express app-mu

describe('Auth API', () => {
  it('Register user baru', async () => {
    const res = await request(app)
      .post('/api/auth/register')
      .send({
        nama: 'Test User',
        email: `testuser${Date.now()}@mail.com`,
        password: 'password123',
        peran: 'konsumen'
      });
    expect(res.statusCode).toBe(201);
    expect(res.body).toHaveProperty('user');
  });

  it('Login user', async () => {
    // Daftar dulu user baru
    const email = `testuser${Date.now()}@mail.com`;
    const password = 'password123';
    await request(app)
      .post('/api/auth/register')
      .send({ nama: 'Test User', email, password, peran: 'konsumen' });

    // Test login
    const res = await request(app)
      .post('/api/auth/login')
      .send({ email, password });
    expect(res.statusCode).toBe(200);
    expect(res.body).toHaveProperty('token');
  });
});
