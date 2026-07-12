package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class ChatbotTest extends BaseTest {

    @Test
    public void testTC25A_ToggleChatbot() {
        driver.get(baseUrl + "/");
        
        // Wait for floating button to load
        WebElement chatbotBtn = waitForElementClickable(By.id("chatbot-toggle"), 5);
        chatbotBtn.click();
        
        // Modal panel should be visible
        WebElement chatbotPanel = waitForElementVisible(By.id("chatbot-panel"), 5);
        assertTrue(chatbotPanel.isDisplayed(), "Chatbot modal panel should open");
        
        // Close modal
        WebElement closeBtn = waitForElementClickable(By.id("chatbot-close"), 5);
        closeBtn.click();
        
        // Wait and assert that it becomes hidden
        try { Thread.sleep(800); } catch (InterruptedException e) {}
        assertFalse(chatbotPanel.isDisplayed(), "Chatbot panel should be hidden after clicking close");
    }

    @Test
    public void testTC26A_SendMessageValid() {
        driver.get(baseUrl + "/");
        
        // Open chatbot
        waitForElementClickable(By.id("chatbot-toggle"), 5).click();
        
        // Fill input query
        WebElement chatInput = waitForElementVisible(By.id("chatbot-input"), 5);
        WebElement sendBtn = waitForElementClickable(By.id("chatbot-send"), 5);
        
        chatInput.sendKeys("apakah dekorasi pelaminan tersedia?");
        sendBtn.click();
        
        // Verify message bubble appears in message container
        WebElement userBubble = waitForElementVisible(By.xpath("//div[contains(text(), 'apakah dekorasi pelaminan tersedia')]"), 5);
        assertNotNull(userBubble, "User message bubble should appear in container");
        
        // Wait up to 10 seconds for Groq API response to complete typing animation and return reply
        WebElement responseBubble = waitForElementVisible(By.xpath("//div[contains(@class, 'bg-white') and not(contains(text(), 'apakah dekorasi'))]"), 12);
        assertNotNull(responseBubble, "AI chatbot response bubble should appear");
        assertTrue(responseBubble.getText().length() > 0, "Response content should not be empty");
    }

    @Test
    public void testTC27B_SendMessageEmpty() {
        driver.get(baseUrl + "/");
        
        // Open chatbot
        waitForElementClickable(By.id("chatbot-toggle"), 5).click();
        
        WebElement chatInput = waitForElementVisible(By.id("chatbot-input"), 5);
        WebElement sendBtn = waitForElementClickable(By.id("chatbot-send"), 5);
        
        chatInput.clear();
        
        // Verify submit/send button is disabled or not clickable when empty
        String isDisabled = sendBtn.getAttribute("disabled");
        assertTrue(isDisabled != null || !sendBtn.isEnabled(), "Send button should be disabled when input is empty");
    }
}
