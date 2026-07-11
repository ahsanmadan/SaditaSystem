import { expect, test, type Page } from '@playwright/test';

async function login(page: Page, email: string, password: string) {
  await page.goto('/login');
  await page.getByLabel(/username\s*\/\s*email/i).fill(email);
  await page.getByLabel('Password').fill(password);
  await page.getByLabel(/saya bukan robot/i).check();
  await page.getByRole('button', { name: 'Login' }).click();
}

test('Admin Login', async ({ page }) => {
  await login(
    page,
    process.env.E2E_ADMIN_EMAIL ?? 'admin',
    process.env.E2E_ADMIN_PASSWORD ?? 'admin',
  );

  await expect(page).toHaveURL(/\/admin/);
  await expect(page.getByRole('heading', { name: /dashboard operasional|dasbor operasional/i })).toBeVisible();
});

test('Owner-only User Deletion', async ({ page }) => {
  test.skip(!process.env.E2E_DELETE_USER_ID, 'Set E2E_DELETE_USER_ID untuk menguji penghapusan user owner-only.');

  await login(
    page,
    process.env.E2E_OWNER_EMAIL ?? 'admin',
    process.env.E2E_OWNER_PASSWORD ?? 'admin',
  );

  await page.goto('/admin/users/manage');

  const targetId = process.env.E2E_DELETE_USER_ID as string;
  const deleteForm = page.locator(`form[action$="/admin/users/${targetId}"]`);

  await expect(deleteForm).toBeVisible();
  await deleteForm.getByRole('button', { name: /hapus/i }).click();

  await expect(page).toHaveURL(/\/admin\/users\/manage/);
  await expect(page.getByText(/berhasil dihapus/i)).toBeVisible();
});
