package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class LoginTest extends BaseTest {

    @Test
    public void testTC01A_LoginSuccess() {
        driver.get(baseUrl + "/login");
        
        // Use explicit waits to make sure fields are ready before typing
        WebElement emailInput = waitForElementVisible(By.id("email"), 5);
        WebElement passwordInput = waitForElementVisible(By.id("password"), 5);
        WebElement botCheckbox = waitForElementClickable(By.id("verify_bot"), 5);
        WebElement submitBtn = waitForElementClickable(By.cssSelector("button[type='submit']"), 5);
        
        // Enter valid credentials
        emailInput.clear();
        emailInput.sendKeys("admin");
        passwordInput.clear();
        passwordInput.sendKeys("admin");
        
        // Click bot verification
        if (!botCheckbox.isSelected()) {
            botCheckbox.click();
        }
        
        submitBtn.click();
        
        // Wait and check if redirected to admin page
        assertTrue(waitForUrlContains("/admin", 5), "Should redirect to admin dashboard");
    }

    @Test
    public void testTC01B_LoginWrongCredentials() {
        driver.get(baseUrl + "/login");
        
        WebElement emailInput = waitForElementVisible(By.id("email"), 5);
        WebElement passwordInput = waitForElementVisible(By.id("password"), 5);
        WebElement botCheckbox = waitForElementClickable(By.id("verify_bot"), 5);
        WebElement submitBtn = waitForElementClickable(By.cssSelector("button[type='submit']"), 5);
        
        emailInput.clear();
        emailInput.sendKeys("wrongadmin");
        passwordInput.clear();
        passwordInput.sendKeys("wrongpass");
        
        if (!botCheckbox.isSelected()) {
            botCheckbox.click();
        }
        
        submitBtn.click();
        
        // Should stay on login page and display error
        assertTrue(driver.getCurrentUrl().contains("/login"), "Should stay on login page");
        
        // Check for validation error messages
        WebElement errorMsg = waitForElementVisible(By.xpath("//*[contains(text(), 'tidak cocok dengan catatan kami') or contains(text(), 'Credentials')]"), 5);
        assertNotNull(errorMsg, "Error message should be shown");
    }

    @Test
    public void testTC01C_LoginEmptyFields() {
        driver.get(baseUrl + "/login");
        
        WebElement emailInput = waitForElementVisible(By.id("email"), 5);
        WebElement passwordInput = waitForElementVisible(By.id("password"), 5);
        WebElement submitBtn = waitForElementClickable(By.cssSelector("button[type='submit']"), 5);
        
        emailInput.clear();
        passwordInput.clear();
        
        // Clicking submit without filling required fields (HTML5 validation will prevent submission)
        submitBtn.click();
        
        // Url should remain same
        assertTrue(driver.getCurrentUrl().contains("/login"), "Should stay on login page");
    }

    @Test
    public void testTC01D_LoginWithoutBotCheck() {
        driver.get(baseUrl + "/login");
        
        WebElement emailInput = waitForElementVisible(By.id("email"), 5);
        WebElement passwordInput = waitForElementVisible(By.id("password"), 5);
        WebElement submitBtn = waitForElementClickable(By.cssSelector("button[type='submit']"), 5);
        
        emailInput.clear();
        emailInput.sendKeys("admin");
        passwordInput.clear();
        passwordInput.sendKeys("admin");
        
        // Do NOT click the verify_bot checkbox
        submitBtn.click();
        
        // HTML5 validation requires the checkbox to be checked. URL stays the same.
        assertTrue(driver.getCurrentUrl().contains("/login"), "Should not submit form and stay on login page");
    }
}
